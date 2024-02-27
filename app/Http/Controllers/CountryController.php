<?php

namespace App\Http\Controllers;
use App\Models\Marketplace;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Country;

class CountryController extends Controller
{
    public function selectCountry()
    {
        $countries = Country::all()->pluck('name', 'code');
//        dd($countries);
        return view('frontend.country', compact('countries'));
    }

    public function postCountry(Request $request)
    {
        $selectedCountryCode = $request->input('country');

        $countryRoutes = [
            'PK' => 'pakistan-route',
            'IN' => 'india-route',
            'AE' => 'uae-route'
        ];

        if (array_key_exists($selectedCountryCode, $countryRoutes)) {
            $selectedRoute = $countryRoutes[$selectedCountryCode];
            $selectedCountry = Country::where('code', $selectedCountryCode)->first();
            session(['selectedCountry' => $selectedCountry]);

            return redirect()->route($selectedRoute);
        }

        return redirect('/');
    }


    public function showPakistan()
    {
        // Selected country details
        $selectedCountryName = 'Pakistan';
        $selectedCountryCode = 'PK';

        // Get the Country model for the selected country
        $country = Country::where('code', $selectedCountryCode)->first();

        // Check if the country is not found
        if (!$country) {
            abort(404, 'Country not found');
        }

        // Get the Marketplaces where country_id is the same as the selected country
        $markets = Marketplace::where('country_id', $country->id)->get();

        // Get the Categories where market_id is 1 (you may need to adjust this condition)
        $categories = Category::where('market_id', 1)->get();

        // Pass the data to the view
        return view('frontend.pages.pakistan', compact('categories', 'markets'));
    }

    public function showIndia()
    {

        // Selected country details
        $selectedCountryName = 'India';
        $selectedCountryCode = 'IN';

        // Get the Country model for the selected country
        $country = Country::where('code', $selectedCountryCode)->first();

        // Check if the country is not found
        if (!$country) {
            abort(404, 'Country not found');
        }

        // Get the Marketplaces where country_id is the same as the selected country
        $markets = Marketplace::where('country_id', $country->id)->get();

        // Get the Categories where market_id is 1 (you may need to adjust this condition)
        $categories = Category::where('market_id', 2)->get();


        // Pass the data to the view
        return view('frontend.pages.india', compact('categories', 'markets'));
    }

    public function showUae()
    {

        // Selected country details
        $selectedCountryName = 'United Arab Emirates';
        $selectedCountryCode = 'AE';

        // Get the Country model for the selected country
        $country = Country::where('code', $selectedCountryCode)->first();

        // Check if the country is not found
        if (!$country) {
            abort(404, 'Country not found');
        }

        // Get the Marketplaces where country_id is the same as the selected country
        $markets = Marketplace::where('country_id', $country->id)->get();

        // Get the Categories where market_id is 1 (you may need to adjust this condition)
        $categories = Category::where('market_id', 3)->get();


        // Pass the data to the view
        return view('frontend.pages.uae', compact('categories', 'markets'));
    }

    public function showCities($countryCode)
    {
        $cities = ['City 1', 'City 2', 'City 3'];
        return view('cities', compact('cities', 'countryCode'));
    }

    public function selectCity(Request $request)
    {
        $selectedCity = $request->input('city');
        return view('home', compact('selectedCity'));
    }
    private function getMarketplacesByCountry($countryCode)
    {
        $country = Country::where('code', $countryCode)->firstOrFail();
        return $country->marketplaces; // Assuming you have a relationship defined in the Country model
    }


}
