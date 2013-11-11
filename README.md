Amazon-S3-PHP-Model
===================

Model class to interact with Amazon Simple Storage Service (S3).

Prerequisites
==================
1. Amazon Web Services Access Key.
2. Amazon Web Services Secret Key.

Class Methods
==================
1. createBucket(<Bucket name>) : Create new bucket in Amazon S3.
2. createBucket(<Bucket name>, <Location>) : Create new bucket in specific region of Amazon S3.
3. getAllBucketName() : Return all the buckets available.
4. useBucket(<Bucket name>) : Assign certain bucket for file operation.
5. uploadFile(<File path>, <New filename>) : Upload a file into Amazon S3 with its absolute path.
6. readFile(<Filename>) : Read content of a file in Amazon S3.
7. writeFile(<Filename>, <Data>) : Write content to a file in Amazon S3.
