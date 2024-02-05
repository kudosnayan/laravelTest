<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Google\Service\Sheets;
use Google\Service\Sheets\Spreadsheet;
use Google\Service\Sheets\ValueRange;

class OpenGoogleSheetController extends Controller
{
    public function openGoogleSheet(User $user)
    {
        // Use the credentials JSON file you downloaded
        $client = new GoogleClient();
        $client->setAuthConfig(storage_path('/token/client_secret_947903179302-vscp33e4hnf7pje50otssgfmg9lsp2d3.apps.googleusercontent.com.json'));
        $client->setScopes([Sheets::SPREADSHEETS]);

        $service = new Sheets($client);
        $spreadsheetId = 'your-spreadsheet-id';

        // Prepare user data for the spreadsheet
        $userData = [
            ['Name', 'Email'],
            [$user->name, $user->email],
            // Add other fields as needed
        ];

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet([
            'properties' => [
                'title' => $userData,
            ],
        ]);
        dd($spreadsheet);
        $spreadsheet = $service->spreadsheets->create($spreadsheet);
        $sheetId = $spreadsheet->getSpreadsheetId();

        // Write data to the spreadsheet
        $range = 'Sheet1';
        $body = new ValueRange(['values' => $userData]);
        $service->spreadsheets_values->update($sheetId, $range, $body, ['valueInputOption' => 'RAW']);

        // Redirect the user to the created spreadsheet
        $spreadsheetUrl = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}";
        return redirect()->away($spreadsheetUrl);
    }
}
