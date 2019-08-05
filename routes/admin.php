<?php

Route::get('pages', 'PagesController@index')->name('pages.list');
Route::get('pages/delete/{id}', 'PagesController@delete')->name('pages.delete');
Route::get('pages/edit/{id}', 'PagesController@edit')->name('pages.edit');
Route::post('pages/update/{id}', 'PagesController@update')->name('pages.update');
Route::get('pages/create', 'PagesController@create')->name('pages.create');
Route::post('pages/store', 'PagesController@store')->name('pages.store');
