<?php
namespace App\Repositories;

use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffRepository
{
    public function all()
    {
        return Staff::all();
    }

    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return Staff::create($data);
    }

    public function find($id)
    {
        return Staff::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $staff = Staff::findOrFail($id);
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $staff->update($data);
        return $staff;
    }

    public function delete($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();
        return $staff;
    }

    public function trashed()
    {
        return Staff::onlyTrashed()->get();
    }

    public function restore($id)
    {
        $staff = Staff::withTrashed()->findOrFail($id);
        $staff->restore();
        return $staff;
    }

    public function forceDelete($id)
    {
        $staff = Staff::withTrashed()->findOrFail($id);
        $staff->forceDelete();
        return $staff;
    }
}
