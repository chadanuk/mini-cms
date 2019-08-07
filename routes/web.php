<?php

Route::get('{page}', 'PagesController@show')->where('page', '.*');
