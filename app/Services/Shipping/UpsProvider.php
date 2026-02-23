<?php

namespace App\Services\Shipping;

class UpsProvider
{
    private $fields = [];
    
    /**
     * Add field for UPS API request (replicate UpsRating->addField line 9)
     */
    public function addField($field, $value)
    {
        $this->fields[$field] = $value;
    }
    
    /**
     * Process UPS rating request (replicate UpsRating->processRate lines 12-46)
     * 
     * @return array [response, status_code]
     */
    public function processRate()
    {
        try {
            $rateData = $this->getProcessRate();
            $rateData = json_encode($rateData);
            
            // Curl start to call UPS rating API (lines 17-32)
            $ch = curl_init(config('ups.urls.rating'));
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $rateData);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            
            if (!($res = curl_exec($ch))) {
                $error = curl_error($ch);
                curl_close($ch);
                return [$error, 403];
            }
            curl_close($ch);
            
            // Parse response (lines 34-41)
            if (is_string($res)) {
                $resObject = json_decode($res);
            }
            
            if (isset($resObject->Fault) && !empty($resObject->Fault)) {
                return [$res, 403];
            } else if (isset($resObject->RateResponse) && !empty($resObject->RateResponse)) {
                return [$res, 200];
            }
            
            return [$res, 403];
        } catch (\Exception $ex) {
            return [$ex->getMessage(), 403];
        }
    }
    
    /**
     * Build UPS API request payload (replicate UpsRating->getProcessRate lines 47-105)
     * 
     * @return array
     */
    private function getProcessRate()
    {
        // UPS Security credentials (lines 48-53)
        $userNameToken = [
            'Username' => config('ups.account.userid'),
            'Password' => config('ups.account.passwd'),
        ];
        
        $UPSSecurity = [
            'UsernameToken' => $userNameToken,
        ];
        
        $accessLicenseNumber = [
            'AccessLicenseNumber' => config('ups.account.access'),
        ];
        
        $UPSSecurity['ServiceAccessToken'] = $accessLicenseNumber;
        $request['UPSSecurity'] = $UPSSecurity;
        
        // Request options (lines 55-56)
        $option['RequestOption'] = 'Shop';
        $request['RateRequest']['Request'] = $option;
        
        // Pickup type (lines 58-60)
        $pickuptype = [
            'Code' => '01',
            'Description' => 'Daily Pickup',
        ];
        $request['PickupType'] = $pickuptype;
        
        // Customer classification (lines 62-64)
        $customerclassification = [
            'Code' => '01',
            'Description' => 'Classfication',
        ];
        $request['CustomerClassification'] = $customerclassification;
        
        // Shipper information (lines 66-74)
        $shipper = [
            'Name' => config('ups.shipper.Name'),
            'ShipperNumber' => config('ups.account.shipperNumber'),
        ];
        
        $address = [
            'AddressLine' => config('ups.shipper.AddressLine'),
            'City' => config('ups.shipper.City'),
            'StateProvinceCode' => config('ups.shipper.StateProvinceCode'),
            'PostalCode' => config('ups.shipper.PostalCode'),
            'CountryCode' => config('ups.shipper.CountryCode'),
        ];
        
        $shipper['Address'] = $address;
        $shipment['Shipper'] = $shipper;
        
        // Ship to information (lines 76-83)
        $shipto = [
            'Name' => $this->fields['ShipTo_Name'],
        ];
        
        $addressTo = [
            'AddressLine' => $this->fields['ShipTo_AddressLine'],
            'City' => $this->fields['ShipTo_City'],
            'StateProvinceCode' => $this->fields['ShipTo_StateProvinceCode'],
            'PostalCode' => $this->fields['ShipTo_PostalCode'],
            'CountryCode' => $this->fields['ShipTo_CountryCode'],
        ];
        
        $shipto['Address'] = $addressTo;
        $shipment['ShipTo'] = $shipto;
        
        // Service (lines 85-87)
        $service = [
            'Code' => '03',
            'Description' => 'Service Code',
        ];
        $shipment['Service'] = $service;
        
        // Package information (lines 88-102)
        $package = [];
        $packaging = [
            'Code' => '02',
            'Description' => 'Rate',
        ];
        $package['PackagingType'] = $packaging;
        
        // Calculate total weight (lines 92-95)
        $weight = 0;
        foreach ($this->fields['dimensions'] as $dimension) {
            $weight = $weight + ($dimension['Weight'] * $dimension['Qty']);
        }
        
        $punit = [
            'Code' => 'LBS',
            'Description' => 'Pounds',
        ];
        
        $packageweight = [
            'Weight' => "$weight",
            'UnitOfMeasurement' => $punit,
        ];
        
        $package['PackageWeight'] = $packageweight;
        
        $shipment['Package'] = [$package];
        $request['RateRequest']['Shipment'] = $shipment;
        
        return $request;
    }
}
