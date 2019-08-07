<?php

Route::get('pages', 'PagesController@index')->name('minicms.pages.list');
Route::get('pages/delete/{id}', 'PagesController@delete')->name('minicms.pages.delete');
Route::get('pages/edit/{id}', 'PagesController@edit')->name('minicms.pages.edit');
Route::post('pages/update/{id}', 'PagesController@update')->name('minicms.pages.update');
Route::get('pages/create', 'PagesController@create')->name('minicms.pages.create');
Route::post('pages/store', 'PagesController@store')->name('minicms.pages.store');
