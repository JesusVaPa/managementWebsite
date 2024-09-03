<?php
declare(strict_types=1);

namespace GivenCode\Domain;

use GivenCode\Exceptions\ValidationException;

class WebpageRoute extends AbstractRoute
{

    private string $webpagePath;

    /**
     * @throws ValidationException
     */
    public function __construct(string $uri, string $webpage_path)
    {
        parent::__construct($uri);
        $webpage_path = PRJ_PAGES_DIR . $webpage_path;
        if (!file_exists($webpage_path)) {
            throw new ValidationException("WebpageRoute specified file at path [$webpage_path] does not exists.");
        }
        $this->webpagePath = $webpage_path;
    }

    public function route(): void
    {
        include $this->webpagePath;
    }
}