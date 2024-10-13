<?php

use App\Http\Controllers\SymptomesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['verify' => true]);
//Auth
Route::get('/logout', 'Auth\LogoutController@logout')->name('auth.logout');
Route::post('/webhook', 'WebhookController@index')->name('webhook');
/*  Accesible par les Inscrits */
Route::group([
    "middleware"=>"level:1"
],function () {
    Route::get('/compte/verify','VerifyController@email')->name("compte.verify");
});
/*  Accesible par les Utilisateurs */
Route::group([
    "middleware"=>"level:2"
],function () {
    Route::get('/compte/verify/conseiller','VerifyController@conseiller')->name("compte.verify.conseiller");
});

/*  Accesible par les Praticiens */
Route::group([
    "middleware"=>"level:3"
],function () {
    Route::post('/contact/send', 'ContactController@send');
    /*  Accesible par tous */
    //Changement de langue
    Route::get('/locale/{lang}', 'LocaleController@handle')->name('locale.handle');
    // Mentions légales
    Route::get('/mentions', 'MentionsController@index')->name('mentions.index');
    // Politique de confidentialité
    Route::get('/politique', 'PolitiqueController@index')->name('politique.index');
    // Contactez-nous
    Route::get('/contact', 'ContactController@index')->name('contact.index');
    // Accueil
    Route::get('/', 'AccueilController@index')->name('accueil.index');

    //Compte
    Route::get('/compte', 'CompteController@index')->name('compte.index');
    Route::post('/compte_email', 'CompteController@updateEmail')->name('compte.email');
    Route::post('/compte_password', 'CompteController@updatePassword')->name('compte.password');

    // Stripe
    Route::get('/stripe', 'StripeController@index')->name('stripe.index');
    Route::get('/stripe/checkout/{price}', 'StripeController@checkout')->name('stripe.checkout');
    Route::get('/stripe/success', 'StripeController@success')->name('stripe.success');
    Route::get('/stripe/cancel', 'StripeController@cancel')->name('stripe.cancel');
    Route::get('/stripe/customer', 'StripeController@createCustomerPortalSession')->name('stripe.createCustomerPortalSession');

});


/*  Accesible par les Praticiens Abonnées*/
Route::group([
    "middleware"=>"level:3",
    "middleware"=>"sub"
],function () {
    // Ingrédients
    Route::get('/ingredients', 'IngredientsController@index')->name('ingredients.index');
    Route::get('/ingredients/{Selectedlang}', 'IngredientsController@liste')->name('ingredients.liste');
    Route::get('/ingredients/{Selectedlang}/search', 'IngredientsController@search')->name('ingredients.search');
    Route::get('/ingredients/{Selectedlang}/{ingredient}', 'IngredientsController@show')->name('ingredients.show');

    // Formules
    Route::get('/formules', 'FormulesController@index')->name('formules.index');
    Route::get('/formules/{Selectedlang}', 'FormulesController@liste')->name('formules.liste');
    Route::get('/formules/{Selectedlang}/search', 'FormulesController@search')->name('formules.search');
    Route::get('/formules/{Selectedlang}/{formule}', 'FormulesController@show')->name('formules.show');
    Route::get('/formules/{Selectedlang}/{formule}/search', 'FormulesController@symptomeSearch')->name('formules.symptomeSearch');

    // Expertise
    Route::get('/expertise', 'ExpertiseController@index')->name('expertise.index');
    Route::get('/expertise/symptomes', 'ExpertiseController@symptome')->name('expertise.symptome');
    Route::get('/expertise/syndromes', 'ExpertiseController@syndrome')->name('expertise.syndrome');
    Route::get('/expertise/resultat', 'ExpertiseController@show')->name('expertise.show');

    // Syndromes
    Route::get('/syndromes', 'SyndromesController@liste')->name('syndromes.liste');
    Route::get('/syndromes/search', 'SyndromesController@search')->name('syndromes.search');
    Route::get('/syndromes/{syndrome}', 'SyndromesController@show')->name('syndromes.show');
    Route::get('/syndromes/{syndrome}/search', 'SyndromesController@symptomeSearch')->name('syndromes.symptomeSearch');

    // Conseillers
    Route::get('/conseillers', 'ConseillersController@index')->name('conseillers.index');
    Route::get('/conseillers/liste', 'ConseillersController@listeForm')->name('conseillers.listeForm');
    Route::get('/conseillers/liste/search', 'ConseillersController@liste')->name('conseillers.liste');
    Route::get('/conseillers/localisation', 'ConseillersController@localisation')->name('conseillers.localisation');
    Route::get('/conseillers/localisation/CP', 'ConseillersController@localisationCP')->name('conseillers.localisationCP');
    Route::get('/conseillers/code', 'ConseillersController@code')->name('conseillers.code');
    Route::post('/conseillers/code', 'ConseillersController@codeSent')->name('conseillers.codeSent');
    Route::get('/conseillers/{praticien}', 'ConseillersController@show')->name('conseillers.show');

    // Actualites
    Route::get('/actualites', 'ActualitesController@index')->name('actualites.index');
    Route::get('/actualites/{actualite}', 'ActualitesController@show')->name('actualites.show');
});

/*  Accesible par les Praticiens */
Route::group([
    "middleware"=>"level:3",
],function () {
    Route::get('/compte/conseillers', 'CompteConseillersController@index')->name('compte.conseillers.index');
    Route::get('/compte/conseillers/create', 'CompteConseillersController@create')->name('compte.conseillers.create');
    Route::post('/compte/conseillers', 'CompteConseillersController@store')->name('compte.conseillers.store');
    Route::get('/compte/conseillers/show', 'CompteConseillersController@show')->name('compte.conseillers.show');
    Route::get('/compte/conseillers/edit', 'CompteConseillersController@edit')->name('compte.conseillers.edit');
    Route::patch('/compte/conseillers/update', 'CompteConseillersController@update')->name('compte.conseillers.update');
    Route::delete('/compte/conseillers/show', 'CompteConseillersController@destroy')->name('compte.conseillers.destroy');
});

/*  Accesible par les Admins */
Route::group([
        'prefix'=>'administrator',
        "middleware"=>"level:5"
],function () {
    //Accueil
    Route::get('/', 'AccueilController@admin')->name('accueil.admin');

    // Symptomes
    Route::get('/symptomes', 'SymptomesController@index')->name('symptomes.index');
    Route::get('/symptomes/create', 'SymptomesController@create')->name('symptomes.create');
    Route::post('/symptomes', 'SymptomesController@store')->name('symptomes.store');
    Route::get('/symptomes/search', 'SymptomesController@search')->name('symptomes.search');
    Route::get('/symptomes/{symptome}', 'SymptomesController@show')->name('symptomes.show');
    Route::get('/symptomes/{symptome}/edit', 'SymptomesController@edit')->name('symptomes.edit');
    Route::patch('/symptomes/{symptome}','SymptomesController@update')->name('symptomes.update');
    Route::delete('/symptomes/{symptome}','SymptomesController@destroy')->name('symptomes.destroy');
    Route::get('/symptomes/{symptome}/traduction', 'SymptomesController@traduction')->name('symptomes.traduction');
    Route::patch('/symptomes/{symptome}/traduction','SymptomesController@updateTraduction')->name('symptomes.updateTraduction');

    // Détails symptomes
    Route::get('/symptomes/{symptome}/syndromes', 'SymptomesController@searchSyndromes')->name('symptomes.searchSyndromes');
    Route::get('/symptomes/{symptome}/formules', 'SymptomesController@searchFormules')->name('symptomes.searchFormules');

    // Traductions
    Route::get('/traductions', 'TraductionsController@index')->name('traductions.index');
    Route::get('/traductions/{traduction}', 'TraductionsController@show')->name('traductions.show');
    Route::get('/traductions/{traduction}/search', 'TraductionsController@search')->name('traductions.search');
    Route::get('/traductions/{traduction}/edit/{reference}', 'TraductionsController@edit')->name('traductions.edit');
    Route::get('/traductions/{traduction}/edit/{reference}/search', 'TraductionsController@editSearch')->name('traductions.editSearch');
    Route::patch('/traductions/{traduction}', 'TraductionsController@update')->name('traductions.update');
    Route::delete('/traductions/{traduction}', 'TraductionsController@destroy')->name('traductions.destroy');
    Route::get('/traductions/create/traduction', 'TraductionsController@create')->name('traductions.create');
    Route::post('/traductions', 'TraductionsController@store')->name('traductions.store');

    // IngrédientsAdmin
    Route::get('/ingredients', 'IngredientsAdminController@index')->name('ingredientsAdmin.index');
    Route::get('/ingredients/create', 'IngredientsAdminController@create')->name('ingredientsAdmin.create');
    Route::post('/ingredients', 'IngredientsAdminController@store')->name('ingredientsAdmin.store');
    Route::get('/ingredients/search', 'IngredientsAdminController@search')->name('ingredientsAdmin.search');
    Route::get('/ingredients/{ingredient}', 'IngredientsAdminController@show')->name('ingredientsAdmin.show');
    Route::get('/ingredients/{ingredient}/edit', 'IngredientsAdminController@edit')->name('ingredientsAdmin.edit');
    Route::patch('/ingredients/{ingredient}', 'IngredientsAdminController@update')->name('ingredientsAdmin.update');
    Route::delete('/ingredients/{ingredient}', 'IngredientsAdminController@destroy')->name('ingredientsAdmin.destroy');
    Route::get('/ingredients/{ingredient}/traduction', 'IngredientsAdminController@traduction')->name('ingredientsAdmin.traduction');
    Route::patch('/ingredients/{ingredient}/traduction','IngredientsAdminController@updateTraduction')->name('ingredientsAdmin.updateTraduction');

    //SyndromesAdmin
    Route::get('/syndromes', 'SyndromesAdminController@index')->name('syndromesAdmin.index');
    Route::get('/syndromes/create', 'SyndromesAdminController@create')->name('syndromesAdmin.create');
    Route::post('/syndromes', 'SyndromesAdminController@store')->name('syndromesAdmin.store');
    Route::get('/syndromes/search', 'SyndromesAdminController@search')->name('syndromesAdmin.search');
    Route::get('/syndromes/{syndrome}', 'SyndromesAdminController@show')->name('syndromesAdmin.show');
    Route::get('/syndromes/{syndrome}/edit', 'SyndromesAdminController@edit')->name('syndromesAdmin.edit');
    Route::patch('/syndromes/{syndrome}','SyndromesAdminController@update')->name('syndromesAdmin.update');
    Route::delete('/syndromes/{syndrome}','SyndromesAdminController@destroy')->name('syndromesAdmin.destroy');
    Route::get('/syndromes/{syndrome}/traduction', 'SyndromesAdminController@traduction')->name('syndromes.traduction');
    Route::patch('/syndromes/{syndrome}/traduction','SyndromesAdminController@updateTraduction')->name('syndromes.updateTraduction');

    //FormulesAdmin
    //Route::get('/formules/{tri?}', 'FormulesAdminController@index')->name('formulesAdmin.index');
    Route::get('/formules', 'FormulesAdminController@index')->name('formulesAdmin.index');
    Route::get('/formules/create', 'FormulesAdminController@create')->name('formulesAdmin.create');
    Route::post('/formules', 'FormulesAdminController@store')->name('formulesAdmin.store');
    Route::get('/formules/search', 'FormulesAdminController@search')->name('formulesAdmin.search');
    Route::get('/formules/{formule}', 'FormulesAdminController@show')->name('formulesAdmin.show');
    Route::get('/formules/{formule}/edit', 'FormulesAdminController@edit')->name('formulesAdmin.edit');
    Route::patch('/formules/{formule}','FormulesAdminController@update')->name('formulesAdmin.update');
    Route::delete('/formules/{formule}','FormulesAdminController@destroy')->name('formulesAdmin.destroy');
    Route::get('/formules/{formule}/traduction', 'FormulesAdminController@traduction')->name('formules.traduction');
    Route::patch('/formules/{formule}/traduction','FormulesAdminController@updateTraduction')->name('formules.updateTraduction');

    // Recherche
    Route::get('/recherche', 'RechercheController@index')->name('recherche.index');
    Route::get('/recherche/search', 'RechercheController@search')->name('recherche.search');

    // ActualitesAdmin
    Route::get('/actualites', 'ActualitesAdminController@index')->name('actualitesAdmin.index');
    Route::get('/actualites/create', 'ActualitesAdminController@create')->name('actualitesAdmin.create');
    Route::post('/actualites', 'ActualitesAdminController@store')->name('actualitesAdmin.store');
    Route::get('/actualites/search', 'ActualitesAdminController@search')->name('actualitesAdmin.search');
    Route::get('/actualites/{actualite}', 'ActualitesAdminController@show')->name('actualitesAdmin.show');
    Route::get('/actualites/{actualite}/edit', 'ActualitesAdminController@edit')->name('actualitesAdmin.edit');
    Route::patch('/actualites/{actualite}', 'ActualitesAdminController@update')->name('actualitesAdmin.update');
    Route::delete('/actualites/{actualite}', 'ActualitesAdminController@destroy')->name('actualitesAdmin.destroy');
    Route::get('/actualites/{actualite}/traduction', 'ActualitesAdminController@traduction')->name('actualitesAdmin.traduction');
    Route::patch('/actualites/{actualite}/traduction','ActualitesAdminController@updateTraduction')->name('actualitesAdmin.updateTraduction');

    // ConseillersAdmin
    Route::get('/conseillers', 'ConseillersAdminController@index')->name('conseillersAdmin.index');
    Route::get('/conseillers/create', 'ConseillersAdminController@create')->name('conseillersAdmin.create');
    Route::post('/conseillers', 'ConseillersAdminController@store')->name('conseillersAdmin.store');
    Route::get('/conseillers/search', 'ConseillersAdminController@search')->name('conseillersAdmin.search');
    Route::get('/conseillers/{conseiller}', 'ConseillersAdminController@show')->name('conseillersAdmin.show');
    Route::get('/conseillers/{conseiller}/edit', 'ConseillersAdminController@edit')->name('conseillersAdmin.edit');
    Route::patch('/conseillers/{conseiller}', 'ConseillersAdminController@update')->name('conseillersAdmin.update');
    Route::delete('/conseillers/{conseiller}', 'ConseillersAdminController@destroy')->name('conseillersAdmin.destroy');

    Route::get('/utilisateurs', 'UtilisateursController@index')->name('utilisateurs.index');
    Route::post('/utilisateurs/edit', 'UtilisateursController@edit')->name('utilisateurs.edit');
    Route::post('/utilisateurs/vip', 'UtilisateursController@vip')->name('utilisateurs.vip');
    Route::post('/utilisateurs/search', 'UtilisateursController@search')->name('utilisateurs.search');
    Route::post('/utilisateurs/delete', 'UtilisateursController@delete')->name('utilisateurs.delete');

    Route::get('/test', 'TestController@index')->name('test');
});
