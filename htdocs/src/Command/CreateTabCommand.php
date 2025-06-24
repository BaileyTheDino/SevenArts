<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Tab;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:create-tab')]
final class CreateTabCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        do {
            $io->title('Create a New Tab');

            $name = $io->ask('Tab name', null, function ($value) {
                if (empty($value)) {
                    throw new RuntimeException('Tab name cannot be empty!');
                }

                return $value;
            });

            $price = (float) $io->ask('Tab price', null, function ($value) {
                if (! is_numeric($value) || $value <= 0) {
                    throw new RuntimeException('Price must be a positive number.');
                }

                return $value;
            });

            $imageUrl = $io->ask('Tab image URL (e.g., images/tab/face-1.svg)', null, function ($value) {
                if (! filter_var($value, \FILTER_VALIDATE_URL) && ! preg_match('/^images\//', $value)) {
                    throw new RuntimeException('Invalid image URL or path. Make sure it starts with "images/".');
                }

                return $value;
            });

            $tab = new Tab();
            $tab->setName($name)
                ->setPrice($price)
                ->setImageUrl($imageUrl);

            // Persist and flush the Tab entity
            $this->entityManager->persist($tab);
            $this->entityManager->flush();

            $io->success(sprintf('Tab "%s" has been created successfully!', $name));

            // Ask if the user wants to create another Tab
            $continue = $io->confirm('Do you want to create another tab?', false);
        } while ($continue);

        $io->success('Tab creation process completed.');

        return Command::SUCCESS;
    }
}
