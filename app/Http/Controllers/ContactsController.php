<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use App\Models\ContactsAddresses;
use Illuminate\Http\Request;
use App\Models\ContactsEmails;
use App\Models\ContactsPhonenumbers;
use Intervention\Image\Facades\Image;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return Contacts::all();
        $users = Contacts::with('contacts_emails', 'contacts_phonenumbers', 'contacts_addresses')->orderBy('contact_name', 'asc')->get();
        return response()->json($users);
        //return view('test', compact('users'));
    }


    public function phones($contact_id)
    {
       
        $phones = ContactsPhonenumbers::where('contact_id',$contact_id)->get();
        //return view('test', compact('phones'));
        return response()->json($phones);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      //  info('Received data from Vue', ['data' => $request->all()]);

       
        $validatedData = $request->validate([
            'name' => 'required|string',
            'emails' => 'array',
            'tempPhone' => 'nullable|string',
            'tempEmail' => 'nullable|string',
            'phoneNumbers' => 'array',
            'addresses' => 'array',
            'addresses.residentialAddress' => 'nullable|string',
            'addresses.mailingAddress' => 'nullable|string',
            'selectedImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
   

        $contact = Contacts::create([
            'contact_name' => $validatedData['name'],
        ]);

        $selectedImage = $request->file('selectedImage');

        if ($selectedImage) {
            $img = Image::make($selectedImage)->resize(250, 250);
            $path = $selectedImage->store('public/images');
            $img->save(storage_path('app/' . $path));
        
            $contact->contact_image = 'images/' . $selectedImage->hashName();
            $contact->save();
        }

        if ($validatedData['tempPhone']) {
            $contact->contacts_phonenumbers()->create([
                'contact_phone_number' => $validatedData['tempPhone']
            ]);
        }
    
        if (isset($validatedData['phoneNumbers'])) {
            foreach ($validatedData['phoneNumbers'] as $phoneNumber) {
                $contact->contacts_phonenumbers()->create([
                    'contact_phone_number' => $phoneNumber
                ]);
            }            
         }

         if ($validatedData['tempEmail']) {
            $contact->contacts_emails()->create([
                'contact_email_address' => $validatedData['tempEmail']
            ]);
        }
    
        if (isset($validatedData['emails'])) {
            foreach ($validatedData['emails'] as $email) {
                $contact->contacts_emails()->create([
                    'contact_email_address' => $email
                ]);
            }            
         }

         if ($validatedData['addresses']) {
                $mailingAddress = $validatedData['addresses']['mailingAddress'];
                $residentialAddress = $validatedData['addresses']['residentialAddress'];

                    if ($mailingAddress || $residentialAddress) {
                        $addressData = [];
                
                        if ($mailingAddress) {
                            $addressData['mailing_address'] = $mailingAddress;
                        }
                
                        if ($residentialAddress) {
                            $addressData['residential_address'] = $residentialAddress;
                        }
               
                        $contact->contacts_addresses()->create($addressData);
            }
        }
     
    return response()->json(['message' => 'A kontakt létrehozva'], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(Contacts $contacts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
      
        $validatedData = $request->validate([
            'contact_name' => 'string|max:255',
            'contacts_phonenumbers' => 'array',
            'contacts_emails' => 'array',
            'contact_id' => 'integer',
            'contacts_addresses' => 'array',
            'contacts_addresses.id' => 'integer',
            'contacts_addresses.residential_address' => 'nullable|string',
            'contacts_addresses.mailing_address' => 'nullable|string',
            'contact_profileimage' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'isProfileImageDeleted' => 'string'

        ]);
       // dd($validatedData['isProfileImageDeleted']);
       
     
        $contact = Contacts::findOrFail($validatedData['contact_id']);
        $contact->contact_name = $validatedData['contact_name'];
        $contact->save();


        $selectedImage = $request->file('contact_profileimage');

        if ($selectedImage) {
            $img = Image::make($selectedImage)->resize(250, 250);
            $path = $selectedImage->store('public/images');
            $img->save(storage_path('app/' . $path));
        
            $contact->contact_image = 'images/' . $selectedImage->hashName();
            $contact->save();
        }

        if(isset($validatedData['isProfileImageDeleted'])){
            $contact->contact_image = null;
            $contact->save();
        }

        if (isset($validatedData['contacts_phonenumbers'])) {
           
            foreach ($validatedData['contacts_phonenumbers'] as $phoneNumber) {

                if (isset($phoneNumber['id'])) { 
                    $existingPhoneNumber = ContactsPhonenumbers::find($phoneNumber['id']);
                   
                    if(isset($phoneNumber['deleted'])){
                       
                        if (($existingPhoneNumber->contact_phone_number != $phoneNumber['contact_phone_number']) && ($phoneNumber['deleted'] === 'false')) {
                            $existingPhoneNumber->update([
                                'contact_phone_number' => $phoneNumber['contact_phone_number']
                            ]);
                        }

                        if (($existingPhoneNumber->contact_phone_number != $phoneNumber['contact_phone_number']) && ($phoneNumber['deleted'] === 'true')) {
                            $existingPhoneNumber->delete();
                        }
 
                        if (($existingPhoneNumber->contact_phone_number == $phoneNumber['contact_phone_number']) && ($phoneNumber['deleted'] === 'true')) {
                            $existingPhoneNumber->delete();
                        }

                    }else{
                        $existingPhoneNumber->update([
                            'contact_phone_number' => $phoneNumber['contact_phone_number']
                        ]);
                    }            
                }else{
               
                $contact->contacts_phonenumbers()->create($phoneNumber);
                }
            }
        }


        if (isset($validatedData['contacts_emails'])) {
           
            foreach ($validatedData['contacts_emails'] as $emailAddress) {

                if (isset($emailAddress['id'])) { 
                    
                    $existingEmailAddress = ContactsEmails::find($emailAddress['id']);
                    
                    if(isset($emailAddress['deleted'])){
                        
                        if (($existingEmailAddress->contact_email_address != $emailAddress['contact_email_address']) && ($emailAddress['deleted'] === 'false')) {
                           
                            $existingEmailAddress->update([
                                'contact_email_address' => $emailAddress['contact_email_address']
                            ]);
                        }

                        if (($existingEmailAddress->contact_email_address != $emailAddress['contact_email_address']) && ($emailAddress['deleted'] === 'true')) {
                            $existingEmailAddress->delete();
                        }
 
                        if (($existingEmailAddress->contact_email_address == $emailAddress['contact_email_address']) && ($emailAddress['deleted'] === 'true')) {
                            $existingEmailAddress->delete();
                        }

                    }else{
                        $existingEmailAddress->update([
                            'contact_email_address' => $emailAddress['contact_email_address']
                        ]);
                    }            
                }else{

                    $contact->contacts_emails()->create($emailAddress);
                }
            }
        }

        if(isset($validatedData['contacts_addresses'])){
            
            if(isset($validatedData['contacts_addresses']['id'])){
               
                $existedAddresses = ContactsAddresses::find($validatedData['contacts_addresses']['id']);
                $existedAddresses->update([
                    'residential_address' => $validatedData['contacts_addresses']['residential_address'],
                    'mailing_address' => $validatedData['contacts_addresses']['mailing_address']
                ]);
            }else{
                
                $contact->contacts_addresses()->create($validatedData['contacts_addresses']);
            }   
        }
       
        return response()->json(['message' => 'Kontakt frissítve'], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contacts $contacts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($contact_id)
    {
        $contact = Contacts::find($contact_id);

        if (!$contact) {
            return response()->json(['message' => 'A kapcsolattartó nem található'], 404);
        }

         $contact->delete();

        return response()->json(['message' => 'A kontakt sikeresen törölve'], 200);
    }
}



