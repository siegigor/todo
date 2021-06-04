<?php

declare(strict_types=1);

namespace App\FetchModel\User;

interface UserFetcher
{
    public function findForAuth(string $email): ?AuthView;
}
