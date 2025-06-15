<?php

namespace App\Services;

use App\Exceptions\InvalidLoginInfoException;
use App\Models\User;
use DateInterval;
use Illuminate\Support\Facades\Auth;
use DateTimeImmutable;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Validator;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Symfony\Component\Clock\Clock;

class AuthService
{

    public function __construct(
        protected UserService $userService
    ) {}
    /**
     * Auth a User
     */
    public function authenticateUser(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $parsedToken = $this->issueToken($user->id);

            return [
                'id' => $user->id,
                'name' => $user->name,
                'token' => $parsedToken
            ];
        }

        throw new InvalidLoginInfoException();
    }

    /**
     * Auth a User
     */
    public function authorizeUser($token)
    {
        return $this->valideToken($token);
    }

    private function issueToken($userId)
    {
        $key = InMemory::base64Encoded(env('JWT_SECRET'));

        $parsedToken = (new JwtFacade())->issue(
            new Sha256(),
            $key,
            static fn(
                Builder $builder,
                DateTimeImmutable $issuedAt
            ): Builder => $builder
                ->expiresAt($issuedAt->modify('+1 months'))
                ->relatedTo((string) $userId)
        );

        return $parsedToken->toString();
    }

    private function valideToken($token)
    {
        try {
            $parser = new Parser(new JoseEncoder());
            $parsedToken = $parser->parse($token);
        } catch (\Exception $e) {
            return false;
        }

        $validator = new Validator();

        $clock = new Clock();
        $constraints = [
            new SignedWith(new Sha256(), InMemory::base64Encoded(env('JWT_SECRET'))),
            new LooseValidAt($clock, DateInterval::createFromDateString('1 months'))
        ];

        try {
            $validator->assert($parsedToken, ...$constraints);
            $claims = $parsedToken->claims()->all();
            
            $userId = $parsedToken->claims()->get('sub');
            $user = $this->userService->readUser($userId);
            Auth::setUser($user);

            return true;
        } catch (RequiredConstraintsViolated $e) {
            return false;
        }
    }
}
