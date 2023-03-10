# Finding Issues via the Call Graph

There are two different ways to optimize any function: either optimize the
code *inside* that function *or* you can try to call the function less times. In
our case, we found that the most problematic function is `UnitOfWork::createEntity`.
But this is a *vendor* function: it's not *our* code. So it's not something that
we can optimize. And honestly, it's probably already super-optimized anyways.

But we *could* try to call it less times... if we can understand *what* in our app
is causing so many calls! The call graph - the big diagram in the center of this page -
holds the answer.

## Call Graph: Visual Function List

Start by clicking on the magnifying glass next to `createEntity`. Woh! That
zoomed us straight to that "node" on the right. Let's zoom out a little.

The first thing to notice is that the call graph is basically a visual
representation of the information from the function list. On the left, it says
this function has two "callers". On the right, we can see those two callers.
But when you're trying to figure out the *big* picture of what's going on,
the call graph is *way* nicer.

## The Critical Path

Let's zoom out a bunch further. Now we can see a clear red path... that eventually
leads to the dark red node down here. This is called the critical path. One of
Blackfire's main jobs is to help us make sense out of all this data. One way it
does that is exactly this: by highlighting the "path" to the biggest problem in
our app.

I'm going to hit this little "home" icon - that will reset the call graph, instead
of centering it around the `createEntity` node. In this view, Blackfire *does*
hide some less-important information around the `createEntity` node, but it gives
us the best overall summary of what's going on: we can clearly see the critical
path. The critical thing to understand is: why is that path in our app so slow?

Let's trace up from the problem node... to find where *our* code starts. Ah,
here's our controller being rendered... and then it renders a template. That's
interesting: it means the problem is coming from *inside* a template... from
inside the `body` block apparently. Then it jumps to a Twig extension called
`getUserActivityText()`... that calls something else
`CommentHelper::countRecentCommentsForUser()`. That's the last function before it
jumps into Doctrine.

## Finding the Problem

So the problem in our code is something around this `getUserActivityText()` stuff.
Let's open up this template: `main/sighting_show.html.twig` - at
`templates/main/sighting_show.html.twig`.

If you look at the site itself, each commenter has a label next to them - like
"hobbyist" or "bigfoot fanatic" - that tells us how *active* they are in the great
and noble quest of finding BigFoot. Over in the Twig template, we *get* this text
via a custom Twig filter called `user_activity_text`:

[[[ code('f1e1bd19b2') ]]]

If you're not familiar with Twig, no problem. The important piece is that
whenever this filter code is hit, a function inside `src/Twig/AppExtension.php`
is called... it's this `getUserActivityText()` method:

[[[ code('25f70cc54f') ]]]

This counts how many "recent" comments this user has made... and via our
complex & proprietary algorithm, it prints the correct label.

Back over in Blackfire, it told us that the last call before Doctrine was
`CommentHelper::countRecentCommentsForUser()` - that's *this* function call
right here!

[[[ code('eebcbf8fdf') ]]]

Let's go open that up - it's in the `src/Service` directory:

[[[ code('91c2e18d2c') ]]]

Ah. If you don't use Doctrine, you might not see the problem - but it's one
that can easily happen no matter *how* you talk to a database. Hold
Command or Ctrl and click the `getComments()` method to jump inside:

[[[ code('36223d01bf') ]]]

Here's the story: each `User` on our site has a database relationship to the
`comment` table: every user can have many comments. The way our code is written,
Doctrine is querying for *all* the data for *every* comment that a User has
*ever* made... simply to then loop over them, and count how many were created within
the last 3 months:

[[[ code('9ef46f3586') ]]]

It's a massively inefficient way to get a simple count. *This* is problem number one.

It seems obvious now that I'm looking at it. But the nice thing is that... it's
not a huge deal that I did this wrong originally - Blackfire points it out. And
not over-obsessing about performance during development helps prevent
premature optimization.

## Attempting the Performance Bug Fix

So let's fix this performance bug. Open up `src/Repository/CommentRepository.php`.
I've already created a function that will use a direct COUNT query to get the
number of recent comments *since* a certain date:

[[[ code('df24b758bf') ]]]

Let's use this... instead of my current, crazy logic.

To access `CommentRepository` inside `CommentHelper` - this *is* a bit specific
to Symfony - create a `public function __construct()` and *autowire* it by adding
a `CommentRepository $commentRepository` argument:

[[[ code('a3de00027e') ]]]

Add a `private $commentRepository` property... and set it in the constructor:
`$this->commentRepository = $commentRepository`:

[[[ code('333c9d8fc0') ]]]

Now... I don't need *any* of this logic. Just return
`$this->commentRepository->countForUser()`. Pass this `$user`... and go steal
the `DateTimeImmutable` from below and use that for the second argument. Celebrate
by killing the rest of the code:

[[[ code('82442bb0ca') ]]]

If we've done a good job, we will hopefully be calling that `UnitOfWork` function
*many* less times - the 23 calls into Doctrine from `CommentHelper` eventually
caused many, many things to be called.

So... let's profile this and see the result! We'll do that next and use Blackfire's
"comparison" feature to *prove* that this change *was* good... except for one
small surprise.
