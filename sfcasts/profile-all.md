# Profile All Requests (Including Ajax)

When you open the browser extension to create a profile, it has a few options that
we've been... ignoring so far.

## Debugging Mode

***TIP
Debugging mode is available via the Debugging add-on.
***

For example, "debugging mode" will tell Blackfire to *disable* pruning - that's
when it removes data for functions that don't take a lot of resources - and also
to disable anonymization - that's when it hides exact details used in SQL queries
and HTTP requests. Debugging mode is nice if something weird is going on.. and
you want to *fully* see what's happening inside a request.

## Distributed Profiling

***TIP
Distributed profiling is available to Premium plan users or higher.
***

Another superpower of Blackfire is called distributed profiling... which you either
won't care about... or it's the most awesome thing ever. Imagine you have a
micro-service architecture where, when you load the page, it makes a few HTTP
requests to some microservices. If you have Blackfire installed on all of your
microservices, Blackfire will automatically create profiles for *every* request
made to *every* app. The final result is a profile with sub-profiles that show you
how the entire infrastructure is working together. It's... pretty incredible.

But, if you want to disable it and *only* profile this main app, you can do that
with this option.

## Disabling Aggregation

The last option is to "disable aggregation". That's a fancy way of telling Blackfire
that you want to make & profile just *one* request, instead of making 10 requests
and averaging the results.

## Profiling All Requests

But what I *really* want to look at is this "Profile all requests" link. Hit
"Record"... then refresh. Woh! Cool! It already made 2 requests! And if I scroll
down a little bit... there's a third! Let's stop right there.

That jumps us to our Blackfire dashboard. These *last* three profiles were just
created: one for the homepage and two others: these are both AJAX calls! Surprise!
Without even thinking about it, we discovered a few extra requests that are part
of that page.

This first one - `/api/github-organization` - is what loads this GitHub repository
info on the right. This makes an API call for the most popular repositories
under the Symfonycasts organization... which is kind of silly... but it was a *great*
way to show how network requests look in Blackfire. We'll see that in a minute.

This other request - for `/_sightings` - is an AJAX call that powers the forever
scroll on the page.

Basically... I like using "profile all requests" in 3 situations. One, to get
an idea of what's all happening on a page. Two, to profile AJAX requests... though
I'll show you another way to do that soon. And three, to profile form submits: fill
out the form, hit "Record", then submit.

## Checking out the Network Requests

Let's look closer at the `/api/github-organization` AJAX profile:
https://bit.ly/sf-bf-github-org. As I mentioned, this makes a network request
to the GitHub API to load repository information. The profile... is almost comical!
Out of the 438 millisecond wall time - 82% of it is from `curl_multi_select()` -
that's the time spent making any API calls.

It's kind of fun to look at this in the CPU dimension, which is only 74
milliseconds. `curl_multi_exec()` is *still* the biggest offender... but it's
a lot less obvious what the critical path is. Compare that with the I/O wait
dimension, which includes network time. The critical path is *ridiculously*
obvious here. This is an extreme example of how different dimensions can be more
or less useful depending on the situation.

One of the interesting things is that... this is *not* the full call graph.
According to this, the code goes straight from `handleRaw()` - which is the first
call into the Symfony Framework - to our controller. In reality, there are many
more function calls in between. Switch back to the CPU dimension. Yep! This
shows more nodes.

*This* is the result of that "pruning" I mentioned a few minutes ago. Blackfire
removes function calls that don't consume any significant resources so that
the critical path - from a *performance* standpoint - is more obvious. The call
graph also automatically hides or shows some info based on what you're zoomed
in on.

In this situation, the critical path is obvious. You can also see the network
requests on top. There are actually *two*: one that returns 1.5 kilobytes and
another that returns 5.

This shows the network time too... but at *least* if you're using the Symfony
HTTP client like I am, these numbers aren't right - they're far too small...
I think that's due to the asynchronous nature of Symfony's HTTP Client.
That's ok - because the overall cost *is* showing up correctly in all the other
dimensions.

So how do we fix this? Should we add some caching? Or somehow try to make only
*one* API call instead of two?  We're actually going to revisit and fix this problem
later. For now, I wanted you to be aware of the "Profile All" feature. Next,
let's check out the Blackfire command-line tool, which has *two* superpowers...
one of which has nothing to do with the command line.
