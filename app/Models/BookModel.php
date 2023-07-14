<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class BookModel extends Model
{
    protected $table = 'books';
    protected $allowedFields = [
        'sapid', 
        'hostname', 
        'loopback',
        'mac_address',
        'creation_date'
    ];
}