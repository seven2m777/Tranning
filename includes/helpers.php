
 



function showError(string $message): void
{
    return "<p style='color: red;'>$message</p>";
}

function isPostRequest(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}
