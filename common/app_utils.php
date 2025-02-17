<?php

function get_app_by_id(int $id) {
    return db_execute('SELECT * FROM apps WHERE id = ?', [$id]);
}

function get_apps_by_owner_id(str $bcid) {
    $results = db_execute_all('SELECT * FROM apps WHERE owner_id = ?', [$bcid]);
    return $results;
}
