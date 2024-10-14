<?php

namespace App\Tests\Entity;

use App\Entity\TodoItem;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class TodoItemTest extends TestCase
{
    public function testTodoItemCreation(): void
    {
        $testTitle = 'title';
        $testDescription = 'description';

        $result = TodoItem::build($testTitle, $testDescription);

        $this->assertEquals($testTitle, $result->getTitle());
        $this->assertEquals($testDescription, $result->getDescription());
        $this->assertEquals(
            (new \DateTime())->getTimestamp(),
            $result->getDueDate()->getTimestamp(),
            '', 1
        );
    }

    /**
     * @dataProvider getValidationTestCases
     */
    public function testValidation($title, $description, $expected)
    {
        $subject = TodoItem::build($title, $description);

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();

        $result = $validator->validate($subject);

        $this->assertEquals($expected, count($result) == 0);
    }

    public function getValidationTestCases()
    {
        return [
            'Succeeds when data is correct' => ['title', 'description', true ],
            'Fails when title is missing' => ['', 'description', false ],
            'Fails when description is missing' => ['title', '', false ],
        ];
    }
}
