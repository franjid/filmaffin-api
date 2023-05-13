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
    private UserDatabaseRepositoryInterface $userDatabaseRepository;
    private MessageBusInterface $bus;

    public function __construct(
        UserDatabaseRepositoryInterface $userDatabaseRepository,
        MessageBusInterface $bus
    )
    {
        $this->userDatabaseRepository = $userDatabaseRepository;
        $this->bus = $bus;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Check if user friends have new ratings');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
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
