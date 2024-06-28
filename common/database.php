<?php

function db_execute($sql, $variables=[]) {
    global $pdo;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($variables);
    return $stmt->fetch();

}

function db_execute_all($sql, $variables=[]): bool|array
{
    global $pdo;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($variables);
    return $stmt->fetchAll();

}

function db_query($sql): bool|PDOStatement
{
    global $pdo;

    return $pdo->query($sql);
}
