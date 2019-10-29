<?php

declare(strict_types=1);

namespace Tests\App\Cli;

use App\Cli\TestCommand;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class TestCommandTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function test() : void
    {
        $command = new TestCommand();

        self::assertSame('Test command', $command->getDescription());

        self::assertSame('The test command help description', $command->getHelp());

        /** @var InputInterface&MockObject $inputInterface */
        $inputInterface = $this->createMock(InputInterface::class);

        /** @var OutputInterface&MockObject $outputInterface */
        $outputInterface = $this->createMock(OutputInterface::class);

        $outputInterface->expects(self::once())
            ->method('writeLn')
            ->with(self::equalTo('<fg=green>Test Command Output</>'));

        $statusCode = $command->run($inputInterface, $outputInterface);

        self::assertSame(0, $statusCode);
    }
}
