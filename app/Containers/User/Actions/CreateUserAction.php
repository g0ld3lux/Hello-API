<?php

namespace App\Containers\User\Actions;

use App\Containers\User\Events\Events\UserCreatedEvent;
use App\Containers\User\Tasks\CreateUserByCredentialsTask;
use App\Port\Action\Abstracts\Action;
use App\Port\Event\Dispatcher\EventsDispatcher;

/**
 * Class CreateUserAction.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class CreateUserAction extends Action
{

    /**
     * @var  \App\Containers\User\Actions\CreateUserByCredentialsTask
     */
    private $createUserByCredentialsTask;

    /**
     * @var  \App\Port\Event\Dispatcher\EventsDispatcher
     */
    private $eventsDispatcher;

    /**
     * CreateUserAction constructor.
     *
     * @param \App\Containers\User\Tasks\CreateUserByCredentialsTask $createUserByCredentialsTask
     * @param \App\Port\Event\Dispatcher\EventsDispatcher            $eventsDispatcher
     */
    public function __construct(
        CreateUserByCredentialsTask $createUserByCredentialsTask,
        EventsDispatcher $eventsDispatcher
    ) {
        $this->createUserByCredentialsTask = $createUserByCredentialsTask;
        $this->eventsDispatcher = $eventsDispatcher;
    }

    /**
     * create a new user object.
     * optionally can login the created user and return it with its token.
     *
     * @param      $email
     * @param      $password
     * @param      $name
     * @param bool $login determine weather to login or not after creating
     *
     * @return mixed
     */
    public function run($email, $password, $name, $login = false)
    {
        $user = $this->createUserByCredentialsTask->run($email, $password, $name, $login);

        // Fire a User Created Event
        $this->eventsDispatcher->fire(New UserCreatedEvent($user));

        return $user;
    }
}