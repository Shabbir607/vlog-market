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
        return view('frontend.country', compact('countries'));
    }

    public function postCountry(Request $request)
    { 
        $selectedCountryCode = $request->input('country');

        $countryRoutes = [
            'PK' => 'pakistan-route',
            'IN' => 'india-route',
            'AE' => 'uae-route',
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
        $selectedCountry = 'Pakistan';
        
        $countryCode = 'PK'; // Replace with the actual country code for Pakistan
        $country = Country::where('code', $countryCode)->first();

        $markets = Marketplace::with("country:id,name")->get();

        // dd($markets);
        return view('pakistan',['markets' => $markets])->with([
            'selectedCountry' => $selectedCountry 
        ]);
    
        $markets = Marketplace::with("country:id,name")->get();
        return view('backend.marketplaces.index', ['markets' => $markets] )->with('country',$country);
    
    }

    public function showUae()
    {
        $selectedCountry = 'UAE'; // Replace with the actual selected country
    
        $country = new Country(); // Assuming you have a Country model
    
        $marketplaces = $country->marketplaces ?? [];
        
        $market = new Marketplace();
    
        $categoryId = 1; // Replace with the actual category ID
        $category = new Category();
    
        try {
            $categoryInfo = $category->getCategoryInfo($categoryId);
        } catch (\Exception $e) {
            // Handle the case where the category is not found
            abort(404, 'Category not found');
        }
    
        return view('uae')->with([
            'selectedCountry' => $selectedCountry,
            'marketplaces' => $marketplaces,
            'categoryInfo' => $categoryInfo,
        ]);
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
