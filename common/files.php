<?php

use kornrunner\Blurhash\Blurhash;

function mime_to_extension($mime) {
    return match ($mime) {
        'image/gif' => '.gif',
        'image/jpeg' => '.jpg',
        'image/png' => '.png',
        'image/webp' => '.webp',
        default => ''
    };
}

/**
 *
 *  Squishes an image into a 128x128 square, and converts it to JPEG
 *  if it is not a JPEG, PNG, WEBP or GIF.
 *
 * @param string $path
 * @return array
 */
function turn_image_into_avatar(string $path): array
{
    $manager = new \Intervention\Image\ImageManager(
        new \Intervention\Image\Drivers\Gd\Driver()
    );

    $image = $manager->read($path);

    // Get mimetype
    $mime = mime_content_type($path);
    $filetype = mime_to_extension($mime);

    $enc = $image->resize(width: 128, height: 128);

    if ($filetype=='') {
        $enc = $image->encodeByMediaType('image/webp');
        $filetype = '.webp';
    } else {
        $enc = $image->encodeByMediaType();
    }

    $image_data = (string) $enc;

    return [
        "data" => $image_data,
        "mime" => $enc->mimetype(),
        'filetype' => $filetype
    ];
}

function get_blurhash_for_image($image): string
{
    // Copied shamelessly from https://github.com/kornrunner/php-blurhash
    $width = imagesx($image);
    $height = imagesy($image);

    $pixels = [];
    for ($y = 0; $y < $height; ++$y) {
        $row = [];
        for ($x = 0; $x < $width; ++$x) {
            $index = imagecolorat($image, $x, $y);
            $colors = imagecolorsforindex($image, $index);

            $row[] = [$colors['red'], $colors['green'], $colors['blue']];
        }
        $pixels[] = $row;
    }

    $components_x = 4;
    $components_y = 3;
    return Blurhash::encode($pixels, $components_x, $components_y);
}

function upload_file($filename, $target, $owner=null) {
    global $bunny_client;

    $bunny_client->upload($filename, $target);

    // Get file mime time
    $mime = mime_content_type($filename);

    $blurhash = '';
    if (str_starts_with('image/', $mime)) {
        $blurhash = get_blurhash_for_image(imagecreatefromstring(file_get_contents($filename)));
    }

    db_execute(
        'INSERT INTO files (path, uploader, blurhash) VALUES (?, ?, ?)',
        [$target, $owner, $blurhash]
    );
}

function upload_raw_data($data, $target, $owner=null) {
    $filename = '/tmp/'.uniqid(more_entropy: true);
    file_put_contents($filename, $data);

    upload_file($filename, $target, $owner);

    unlink($filename);
}

/**
 * @throws \Bunny\Storage\Exception
 * @throws ImagickException
 */
function upload_avatar($data, $user): string
{
    global $bunny_client;

    $img = turn_image_into_avatar($data['tmp_name']);

    $uuid = uniqid(prefix: 'avatar', more_entropy: true);

    $remote_file_name = 'avatars/'.$uuid.$img['filetype'];

    upload_raw_data($img['data'], $remote_file_name);

    $file = db_execute('SELECT id FROM files WHERE path = ? LIMIT 1', [$remote_file_name]);

    $existing_avatar = db_execute('SELECT * FROM avatars WHERE owner = ?', [$user['id']]);

    if (empty($existing_avatar)) {
        db_execute(
            'INSERT INTO avatars (file_id, owner) VALUES (?, ?)',
            [$file['id'], $user['id']]
        );
    } else {
        db_execute(
            'UPDATE avatars SET file_id = ? WHERE owner = ?',
            [$file['id'], $user['id']]
        );
    }

    return 'https://'.BUNNY_STORAGE_ZONE.'.b-cdn.net/'.$remote_file_name;
}

/**
 * @param $id int
 * @return void
 */
function delete_file_by_id(int $id): void
{
    global $bunny_client;

    // Get remote path
    $file = db_execute('select * from files where id = ?', [$id]);

    if (empty($file)) {
        return;
    }

    // Remove reference to the file in the database
    db_execute('delete from files where id = ?', [$id]);
    // Then remove the file
    $bunny_client->delete($file['path']);
}
