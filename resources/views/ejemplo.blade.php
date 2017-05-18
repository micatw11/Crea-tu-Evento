@extends('layouts.app')
@section('content')
<nav class="tabs">
        <div class="container">
          <ul>
              <router-link tag="li" to="/" exact>
                         <a>Home</a>
                  </router-link>


                  <router-link tag="li" to="/about">
                          <a>About</a>
                  </router-link>
                  <router-link tag="li" to="/header">
                          <a>Header</a>
                  </router-link>
          </ul>
        </div>
  </nav>
  @stop