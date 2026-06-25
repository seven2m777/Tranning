<?php

function showError(string $message): string
{
    return "<div class='alert alert-danger'>$message</div>";
}

function isPostRequest(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function cleanInputData(string $data): string
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header("Location: $url");
    exit;
}