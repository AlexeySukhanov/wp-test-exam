Make a music album release library.

A test task for WordPress developers.

Example: https://www.discogs.com


Requirements:

- An artist should be able to produce music releases.
- An artist can be featured in other artists' releases.
- Music release has additional fields:
    - Genre, multiple (Rock, Pop, Electronic, etc.)
    - Style, multiple (Hard Rock, Pop Rock, Synthpop, etc.)
    - Format (Album, Collection, EP, Single, Remix)
    - Country
    - Release Date (Year only)
    - Tracklist (Sorted list of tracks with length)

To do:

1. Create a child theme for Fino and customize the main page, artist page, and music release page.

Main page must have:
- Pagination with per page option
- Sort option (by date, by title, by release date)
- Filters via menu widget (genre, style, format, country, decade) with a total count

Artist page must have:
- General info
- Discography with separation by release format
- Tracks list (title, year)

Release page must have:
- General info
- Tracklist

2. Create a shortcode for the 'Previous watched' carousel. In the artist page, it should possible to see the previously watched artists in the current session. The release page must showcase the previously watched releases. Also, the shortcode should have the current_id parameter to ensure that we do not show the current page in the carousel.

What if we have enabled the HTML cache and our web-server serves cached the static HTML pages?
- In this case, carousel elements will also be cached, this will lead to irrelevant data

Can you make this personalized carousel work right with the enabled HTML cache? 
- No, I did not have time to do this.

What is the best approach to do this?
 - The best way is to fill the contents of the carousel container using an ajax request. An iframe could also be used for this, but this is a less preferred way.

admin panel: 
user: sadmin
pass: 0000

Author's completed page for testing:
{domain_name}/artists/yello/

