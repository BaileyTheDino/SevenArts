<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Product;
use App\Enum\ProductSection;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:create-product')]
final class CreateProductCommand extends Command
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
            $io->title('Create a new Product');

            $name = $io->ask('Product name');
            $price = (float) $io->ask('Product price', null, function ($value) {
                if (! is_numeric($value) || $value <= 0) {
                    throw new RuntimeException('Price must be a positive number.');
                }

                return $value;
            });

            $quantity = $io->ask('Product quantity (e.g., "5kg", "2pcs")');
            $imageUrl = $io->ask('Product image URL');
            $section = $io->choice(
                'Product section',
                array_map(fn (ProductSection $section) => $section->value, ProductSection::cases())
            );

            $product = new Product();
            $product->setName($name)
                ->setPrice($price)
                ->setQuantity($quantity)
                ->setImageUrl($imageUrl)
                ->setSection($section);

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $io->success(sprintf('Product "%s" has been created successfully.', $name));

            $continue = $io->confirm('Do you want to create another product?', false);
        } while ($continue);

        $io->success('Product creation completed.');

        return Command::SUCCESS;
    }
}
