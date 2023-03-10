# Fixing N+1 With a Join?

We made a *huge* leap forward by telling Doctrine to make `COUNT` queries to
count the comments for each `BigFootSighting`... instead of querying for *all* the
comments *just* to count them. That's a big win.

Could we go further... and make a smarter query that can grab all this data
at once? That *is* the classic solution to the N+1 problem: need the data for
some Bigfoot sightings *and* their comments? Add a JOIN and get all the data at
once! Let's give that a try!

## Adding he JOIN

The controller for this page lives at `src/Controller/MainController.php` - it's
the `homepage()` method:

[[[ code('8d5b5c8501') ]]]

To help make the query, this uses a function in
`src/Repository/BigFootSightingRepository.php` - this `findLatestQueryBuilder()`:

[[[ code('4fffc80064') ]]]

*This* method ... if you did some digging ... creates the query that returns
these results.

And... it's fairly simple: it grabs all the records from the `big_foot_sighting`
table, orders them by `createdAt` and sets a max result - a `LIMIT`.

To *also* get the comment data, add `leftJoin()` on `big_foot_sighting.comments`
and alias that joined table as `comments`. Then use `addSelect('comments')` to
not only *join*, but also *select* all the fields from `comment`:

[[[ code('c3d7c0c482') ]]]

Let's... see what happens! To be safe, clear the cache:

```terminal-silent
php bin/console cache:clear
```

And warm it up:

```terminal-silent
php bin/console cache:warmup
```

Now, move over, refresh and profile! I'll call this one: `[Recording] Homepage with join`:
https://bit.ly/sf-bf-join.

Go check it out! Woh! This... looks weird... it looks *worse*! Let's do a
compare from the `EXTRA_LAZY` profile to the new one: https://bit.ly/sf-bf-join-compare.

Wow... this is much, much worse: CPU is way up, I/O... it's up in every category,
especially network: the amount of data that went over the network. We *did* make
less queries - victory! - but they took 8 milliseconds longer. We're now returning
*way* more data than before.

So this was a *bad* change. It seems obvious now - but in a different situation
where you might be doing different things with the data, this *same* solution
could have been the right one! Let's remove the join and rely on the `EXTRA_LAZY`
solution.

## A Smarter Join?

Yes, this *will* mean that we will once again have 27 queries. If you don't like
that, there *is* another solution: you could make the `JOIN` query smarter - it
would look like this:

```php
// src/Repository/BigFootSightingRepository.php
public function findLatestQueryBuilder(int $maxResults): QueryBuilder
{
    return $this->createQueryBuilder('big_foot_sighting')
        ->leftJoin('big_foot_sighting.comments', 'comments')
        ->groupBy('big_foot_sighting.id')
        ->addSelect('COUNT(comments.id) as comment_count')
        ->setMaxResults($maxResults)
        ->orderBy('big_foot_sighting.createdAt', 'DESC');
}
```

The key is that instead of selecting *all* the comment data... which we don't need...
this selects *only* the count. It gets the *exact* data we need, in one query.
From a performance standpoint, it's probably the perfect solution.

But... it has a downside: complexity. Instead of returning an array of
`BigFootSighting` objects, this will return an array of... arrays... where each
has a `0` key that is the `BigFootSighting` object and a `comment_count` key with the count.
It's just... a bit weird to deal with. For example, the template would need to
be updated to take this into account:

```jinja
{% for sightingData in sightings %}
    {% set sighting = sightingData.0 %}
    {% set commentCount = sightingData.comment_count %}

    {# ... #}
        {{ sighting.title }}

        {{ commentCount }}
    {# ... #}
{% endfor %}
```

*And*... because of the pagination that this app is using... the new query would
actually produce an error. So let's keep things how they are now. If the extra
queries ever become a *real* problem on production, *then* we can think about spending
time improving this. Sometimes profiling is about knowing what *not* to fix...
because it may not be worth it.

Next, if you were surprised that we didn't see any evidence of the network request
that the homepage is making to render the SymfonyCasts repository info from
GitHub, that's because the homepage is more complex than it might seem. Let's
use a cool "Profile all" feature to see *all* requests that the homepage makes.
