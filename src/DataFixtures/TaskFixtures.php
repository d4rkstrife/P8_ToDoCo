<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Factory\TaskFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TaskFactory::new()->createMany(20);

        $manager->flush();
    }
}
