<?php

namespace App\Application\Command;

use App\Domain\Event\UserFriendsNewFilmsRatedEvent;
use App\Infrastructure\Interfaces\UserDatabaseRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CheckUsersFriendsNewRatingsCommand extends Command
{
    protected static $defaultName = 'filmaffin:users:check-friends-new-ratings';

    public function __construct(
        private readonly UserDatabaseRepositoryInterface $userDatabaseRepository,
        private readonly MessageBusInterface $bus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Check if user friends have new ratings');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $usersWithFriends = $this->userDatabaseRepository->getUsersWithFriends();

        foreach ($usersWithFriends->getItems() as $user) {
            $userId = $user->getUserId();

            $lastIdUserRatingNotificated = $this->userDatabaseRepository->getLastIdUserRatingNotificated($userId);
            $lastIdUserRatingFromUserFriends = $this->userDatabaseRepository->getLastIdUserRatingFromUserFriends($userId);

            if ($lastIdUserRatingFromUserFriends > $lastIdUserRatingNotificated) {
                $this->bus->dispatch(new UserFriendsNewFilmsRatedEvent($userId));
            }
        }

        return 0;
    }
}
