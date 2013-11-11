<?php
/*
* ---------------------- Amazon S3 Model Class ------------------------
*	Author: Saurav Majumder
*	Date: Nov 11, 2013
*	Description: This class can be used as a model class for interaction with
*	Amazon S3 for creating bucket, retrieving bucket information, creating 
*	file, reading file and writing file.
*
*	@param AWS Access Key and AWS Secret Key are needed to be included in the
*	variables mentioned below.
* --------------------------------------------------------------------
*/
require 'vendor/autoload.php'

use Aws\S3\S3Client;

class connectS3 {
	private $current_bucket; 			// Variable for current bucket in use.
	private $aws_access_key_id = "";	// Put the AWS Access Key of your account.
	private $aws_secret_key = "";		// Put the AWS Secret Key of your account.

	function __construct() {
		// Establish connection with the Amazon S3 with provided credentials.
       	$client = S3Client::factory(array(
			'key'    => $aws_access_key_id,
			'secret' => $aws_secret_key
		));
   	}

   	// Function to create new bucket in the Amazon S3
   	// @param : new bucket name
   	// @result : Return true if bucket is created or return false.
	function createBucket($bucket_name) {
		if($bucket_name == "") return false;

		try {
			$client->createBucket(array('Bucket' => $bucket_name)); 			// Create new bucket with provided name.
			$client->waitUntilBucketExists(array('Bucket' => $bucket_name));	// Check if the new bucket has been created.
			$current_bucket = $bucket_name;										// Assign this new bucket as the current bucket.
			return true;														// Return true when bucket is created.
		} catch (Exception $e) {
    		return false;														// Return false when bucket is not created.
		}
	}

	// Function to create bucket in a specific region.
	// @param : (1) new bucket name, (2) Region (Amazon Specified)
	// @result : Return true if the bucket is created or return false.
	function createBucket($bucket_name, $location) {
		if($bucket_name == "" || $location == "") return false; 				// Check for empty input arguements. 

		try {
			$client->createBucket(array(
    			'Bucket'             => $bucket,								// Create new bucket.
    			'LocationConstraint' => \Aws\Common\Enum\Region::$location
			));
			$client->waitUntilBucketExists(array('Bucket' => $bucket_name));	// Check if the new bucket has been created.
			$current_bucket = $bucket_name;										// Assign this new bucket as the current bucket.
			return true;														// Return true when bucket is created.
		} catch (Exception $e) {
    		return false;														// Return false if the bucket is not created.
		}
	}

	// Function to retrieve all available buckets.
	// @param : None
	// @result : Array of bucket names.
	function getAllBucketName() {
		$array[] = $bucket_names;												// Initialize array for bucket names.
		$result = $client->listBuckets();										// Get list of buckets from Amazon S3.
		foreach ($result['Buckets'] as $bucket) {								
    		array_push($bucket_names, $bucket['Name']);							// Put bucket names in the array.
		}
		return $bucket_names;													// Return the array of backet names.
	}

	// Function to assign a certain bucket as current bucket in use.
	// @param : Name of bucket to be used.
	// @result : Return true if the bucket is found and assigned or return false.
	function useBucket($bucket_name) {
		if($bucket_name == "") return false;									// Input argument validation.

		try {
			$client->waitUntilBucketExists(array('Bucket' => $bucket_name));	// Find the specified bucket.
			$current_bucket = $bucket_name;										// Assign the bucket as the current bucket.
			return true;														// Return true when completed.
		} catch (Exception $e) {
    		return false;														// Return false if the opretion failed.
		}
	}

	// Function to upload a file to Amazon S3.
	// @param : (1) Absolute path of the file to be uploaded (2) Name of that file in S3.
	// @result : Return the URL of the uploaded file in Amazon S3.
	function uploadFile($filePath, $fileName) {
		if($filePath == "" || $fileName == "") return ;							// Input argument validation.
		if($current_bucket == "") return false;									// Check if current bucket is assigned.

		try {
			$result = $client->putObject(array(									// Upload file with provided URL.
	    		'Bucket'     => $current_bucket,
	    		'Key'        => $fileName,
	    		'SourceFile' => $filePath,
	    		'Metadata'   => array(
	        		'Author' => 's3-model',										// Uploaded file author set to 's3-model'
	    		)
			));

			$client->waitUntilObjectExists(array(								// Check if the file has been uploaded or not.
    			'Bucket' => $current_bucket,
    			'Key'    => $fileName    			
			));
		} catch (Exception $e) {
    		return false;
		}

		return $result['ObjectURL'];											// Return the URL of the uploaded file.
	}

	// Function to read a file from Amazon S3.
	// @param : file name.
	// @result : String containing the content of the file. 
	function readFile($fileName) {
		if($fileName == "") return null;										// Input argument validation.

		$result = $client->getObject(array(										// Retrieve the object S3.
    		'Bucket' => $current_bucket,
    		'Key'    => $fileName
		));

		return $result['Body'];													// Return the content of the file
	}

	// Function to write to a specific file.
	// @param : (1) File name, (2) Content
	// @result : True once the write is completed or return false.
	function writeFile($fileName, $data) {
		if($fileName == "") return false;

		$result = $client->putObject(array(
    		'Bucket' => $current_bucket,
    		'Key'    => 'data.txt',
    		'Body'   => $data
		));
	}
}
?>
