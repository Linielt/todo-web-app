<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Validator\ConstraintValidatorFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator;
use Symfony\Component\Validator\Validation;

class UserTest extends TestCase
{
    public function testUserCreation(): void
    {
        $testEmail = 'johnoe@email.com';
        $testPassword = 'password';

        $result = User::build($testEmail, $testPassword);

        $this->assertEquals($testEmail, $result->getEmail());
        $this->assertEquals($testPassword, $result->getPassword());
    }

    /**
     * @dataProvider getValidationTestCases
     */
    public function testValidation($email, $password, $expected)
    {
        $subject = User::build($email, $password);

        $factory = new ConstraintValidatorFactory();
        $factory
            ->addValidator('doctrine.orm.validator.unique', $this->createMock(UniqueEntityValidator::class));

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory($factory)
            ->enableAttributeMapping()
            ->getValidator();

        $result = $validator->validate($subject);

        $this->assertEquals($expected, count($result) == 0);
    }

    public function getValidationTestCases()
    {
        return [
            'Succeeds when data is correct' => [ 'johndoe@email.com', 'password', true ],
            'Fails when email is missing' => [ '', 'password', false ],
            'Fails when password is missing' => ['johnnydoe@email.com', '', false ],
        ];
    }
}
