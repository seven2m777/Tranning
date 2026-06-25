<?php

function showError(string $message): string
{
    return "<div class='alert alert-danger'>$message</div>";
}

function isPostRequest(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

?>