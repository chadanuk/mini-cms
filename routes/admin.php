<?php

Route::get('pages', 'PagesController@index')->name('mini-cms.pages.list');
Route::get('pages/delete/{id}', 'PagesController@delete')->name('mini-cms.pages.delete');
Route::get('pages/edit/{id}', 'PagesController@edit')->name('mini-cms.pages.edit');
Route::post('pages/update/{id}', 'PagesController@update')->name('mini-cms.pages.update');
Route::get('pages/create', 'PagesController@create')->name('mini-cms.pages.create');
Route::post('pages/store', 'PagesController@store')->name('mini-cms.pages.store');
