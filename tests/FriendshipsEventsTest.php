<?php

namespace Tests;

use Demency\Friendships\Events\Accepted;
use Demency\Friendships\Events\Blocked;
use Demency\Friendships\Events\Cancelled;
use Demency\Friendships\Events\Denied;
use Demency\Friendships\Events\Sent;
use Demency\Friendships\Events\Unblocked;
use Illuminate\Support\Facades\Event;

class FriendshipsEventsTest extends TestCase
{
    /**
     * @test
     */
    public function friend_request_is_sent()
    {
        Event::fake();
        $sender = createUser();
        $recipient = createUser();
        $sender->befriend($recipient);
        Event::assertDispatched(Sent::class, function ($event) use ($sender, $recipient) {
            return $event->sender->id === $sender->id && $event->recipient->id === $recipient->id;
        });
    }

    /**
     * @test
     */
    public function friend_request_is_accepted()
    {
        Event::fake();
        $sender    = createUser();
        $recipient = createUser();

        $recipient->befriend($sender);
        $sender->acceptFriendRequest($recipient);

        Event::assertDispatched(Accepted::class, function ($event) use ($sender, $recipient) {
            return $event->sender->id === $sender->id && $event->recipient->id === $recipient->id;
        });
    }

    /**
     * @test
     */
    public function friend_request_is_denied()
    {
        Event::fake();
        $sender    = createUser();
        $recipient = createUser();

        $recipient->befriend($sender);

        $sender->denyFriendRequest($recipient);
        Event::assertDispatched(Denied::class, function ($event) use ($sender, $recipient) {
            return $event->sender->id === $sender->id && $event->recipient->id === $recipient->id;
        });

    }

    /**
     * @test
     */
    public function friend_is_blocked()
    {
        Event::fake();
        $sender    = createUser();
        $recipient = createUser();

        $recipient->befriend($sender);
        $sender->acceptFriendRequest($recipient);
        $sender->blockFriend($recipient);
        Event::assertDispatched(Blocked::class, function ($event) use ($sender, $recipient) {
            return $event->sender->id === $sender->id && $event->recipient->id === $recipient->id;
        });
    }

    /**
     * @test
     */
    public function friend_is_unblocked()
    {
        Event::fake();
        $sender    = createUser();
        $recipient = createUser();

        $recipient->befriend($sender);
        $sender->acceptFriendRequest($recipient);
        $sender->blockFriend($recipient);
        $sender->unblockFriend($recipient);
        Event::assertDispatched(Unblocked::class, function ($event) use ($sender, $recipient) {
            return $event->sender->id === $sender->id && $event->recipient->id === $recipient->id;
        });
    }

    /**
     * @test
     */
    public function friendship_is_cancelled()
    {
        Event::fake();
        $sender    = createUser();
        $recipient = createUser();
        $recipient->befriend($sender);
        $sender->acceptFriendRequest($recipient);
        $sender->unfriend($recipient);
        Event::assertDispatched(Cancelled::class, function ($event) use ($sender, $recipient) {
            return $event->sender->id === $sender->id && $event->recipient->id === $recipient->id;
        });
    }
}
