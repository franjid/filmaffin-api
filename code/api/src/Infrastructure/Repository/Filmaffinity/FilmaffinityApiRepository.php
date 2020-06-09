<?php

namespace App\Infrastructure\Repository\Filmaffinity;

use App\Domain\Entity\UserFilmaffinity;
use App\Infrastructure\Exception\Filmaffinity\CookieNotFoundException;
use App\Infrastructure\Exception\Filmaffinity\InvalidUserPasswordException;
use App\Infrastructure\Exception\Filmaffinity\UserIdNotFoundException;
use App\Infrastructure\Exception\Filmaffinity\UserNameNotFoundException;
use App\Infrastructure\Exception\Filmaffinity\UserTemplateNotValidException;
use App\Infrastructure\Interfaces\FilmaffinityRepositoryInterface;
use Campo\UserAgent;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FilmaffinityApiRepository implements FilmaffinityRepositoryInterface
{
    private HttpClientInterface $client;
    private string $userAgent;

    public function __construct(
        HttpClientInterface $client
    )
    {
        $this->client = $client;
        $this->userAgent = UserAgent::random(['device_type' => 'Mobile']);
    }

    /**
     * @param string $user
     * @param string $password
     *
     * @return UserFilmaffinity
     * @throws CookieNotFoundException
     * @throws InvalidUserPasswordException
     * @throws UserIdNotFoundException
     * @throws UserNameNotFoundException
     * @throws UserTemplateNotValidException
     */
    public function loginUser(string $user, string $password): UserFilmaffinity
    {
        try {
            $response = $this->client->request(
                'POST',
                sprintf(
                    'https://filmaffinity.com/es/account.ajax.php?action=login&username=%s&password=%s',
                    $user,
                    $password
                ),
                ['headers' => ['user-agent' => $this->userAgent]]
            );

            $statusCode = $response->getStatusCode();
            $content = $response->toArray();

            if ($statusCode !== 200 || !isset($content['result']) || $content['result'] !== 0) {
                throw new \Exception();
            }

            $headers = $response->getHeaders();
        } catch (\Throwable $e) {
            throw new InvalidUserPasswordException('Username or password not valid');
        }

        $filmaffinityUserCookie = $this->getUserCookie($headers);
        $templateContent = $this->getUserTemplate($filmaffinityUserCookie);

        $userId = $this->getUserId($templateContent);
        $userName = $this->getUserName($templateContent);

        return new UserFilmaffinity($userId, $userName, $filmaffinityUserCookie);
    }

    /**
     * @param array $headers
     *
     * @return string
     * @throws CookieNotFoundException
     */
    private function getUserCookie(array $headers): string
    {
        foreach ($headers['set-cookie'] as $cookieHeader) {
            if (strpos($cookieHeader, 'FSID=') !== false) {
                preg_match('/^FSID=[[:alnum:]]+;/', $cookieHeader, $matches);

                return $matches[0];
            }
        }

        throw new CookieNotFoundException('FSID cookie not found in headers');
    }

    /**
     * @param string $userTemplateContent
     *
     * @return int
     * @throws UserIdNotFoundException
     */
    private function getUserId(string $userTemplateContent): int
    {
        try {
            preg_match('/(user_profile\.php\?id-user=)(\d+)/', $userTemplateContent, $matches);
            $userId = (int) ($matches[2] ?? null);

            if (!$userId) {
                throw new \Exception();
            }

            return $userId;
        } catch (\Throwable $e) {
            throw new UserIdNotFoundException('User id not found');
        }
    }

    /**
     * @param string $userTemplateContent
     *
     * @return string
     * @throws UserNameNotFoundException
     */
    private function getUserName(string $userTemplateContent): string
    {
        try {
            preg_match('/(<span id="u-n-wrapper">)([[:alnum:]]+)(<\/span>)/', $userTemplateContent, $matches);
            $userName = $matches[2] ?? null;

            if (!$userName) {
                throw new \Exception();
            }

            return $userName;
        } catch (\Throwable $e) {
            throw new UserNameNotFoundException('User name not found');
        }
    }

    /**
     * @param string $userCookie
     *
     * @return string
     * @throws UserTemplateNotValidException
     */
    private function getUserTemplate(string $userCookie): string
    {
        try {
            $response = $this->client->request(
                'POST',
                'https://m.filmaffinity.com/es/tpl.ajax.php?action=getTemplate&name=user-menu',
                [
                    'headers' => [
                        'user-agent' => $this->userAgent,
                        'cookie' => $userCookie,
                    ]
                ]
            );

            $statusCode = $response->getStatusCode();
            $content = $response->toArray();

            if ($statusCode !== 200 || !isset($content['html'])) {
                throw new \Exception();
            }
        } catch (\Throwable $e) {
            throw new UserTemplateNotValidException('User id not found');
        }

        return $content['html'];
    }
}
