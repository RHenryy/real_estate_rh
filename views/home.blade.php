@extends('layouts.layout')
@section('content')
    <section class="about-us">
        <h1>The number one real estate agency conglomerate since 1999</h1>
        <article class="about-us">
            <h2>About us</h2>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Facilis cum necessitatibus saepe quam nesciunt
                nulla, quasi nihil reprehenderit enim labore numquam eligendi aut, a optio officiis molestias in laborum
                nisi!</p>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quos numquam molestiae maiores quam fugit, error,
                dolorum tempore dicta et cum ea maxime cupiditate hic assumenda voluptates magni. Excepturi, sed architecto?
            </p>
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nemo saepe ratione dolores modi ipsum cumque
                molestiae explicabo praesentium nesciunt distinctio aut at obcaecati, consectetur laudantium voluptatem
                eveniet accusantium quae veniam!</p>
            <p><strong>
                    @if (isset($_SESSION['user']) && !empty($_SESSION['user']))
                        <a href="partners" tlte="Apply now to join our network"> Apply now to join our network today!</a>
                </strong></p>
        @else
            <a href="register" title="Create an account to apply"> Create an account and apply to join our network today!</a>
            </strong></p>
            @endif
        </article>
    </section>
    @if (!empty($agencies))
        <section class="latest-agencies">
            <h2>Our latest agencies</h2>
            @component('components.agency_card', ['pagename' => 'home'])
            @endcomponent
        </section>
    @endif
    @if (!empty($properties))
        <section class="latest-properties">
            <h2>Our latest properties</h2>
            @component('components.property_card', ['pagename' => 'home'])
            @endcomponent
        </section>
    @endif
@endsection
