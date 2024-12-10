<?php

namespace App\Http\Controllers;

use App\Exports\TimesheetExport;
use App\Http\Requests\TimesheetRequest;
use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Maatwebsite\Excel\Excel;

class TimesheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Timesheet');
    }

    public function getUserTimesheets($id){
      $user = User::findOrFail($id);
      if (!Gate::allows('timesheet_detail', $user)) {
        Session::flash('flash', [
          'type'=> 'error',
          'message'=> 'Không có quyền xem thông tin này'
        ]);
        return ;
      }
      $timesheets = Timesheet::filter()->where('user_id', $id)->get();
      return Inertia::render('Timesheet', [
        'timesheets' => $timesheets,
        'user_id' => $id
      ]);
    }


    public function checkIn(TimesheetRequest $request, $userId)
    {
        if (!Gate::allows('check_in')) {
          Session::flash('flash', [
            'type'=> 'error',
            'message'=> 'Không thể chấm công lúc này'
          ]);
          return ;
        }
        $validated = $request->validated();
        $validated['created_by'] = $userId;
        $validated['date'] = Carbon::now()->format('Y-m-d');
        $validated['check_in_time'] = Carbon::now()->format('Y-m-d H:i:s');
        Session::flash('flash', [
          'type'=> 'success',
          'message'=> 'Ghi nhận thời gian vào làm'
        ]);
        return Timesheet::create($validated);
    }

    public function checkOut(TimesheetRequest $request)
    {
        if (!Gate::allows('check_out')) {
          Session::flash('flash', [
            'type'=> 'error',
            'message'=> 'Không thể chấm công lúc này'
          ]);
          return ;
        }
        $user = auth()->user();
        $timesheetId = Timesheet::where('user_id', $user->id)->where('date', date('Y-m-d'))->whereNull('check_out_time')->value('id');
        $validated = $request->validated();
        $validated['check_out_time'] = Carbon::now()->format('H:i:s');
        Session::flash('flash', [
          'type'=> 'success',
          'message'=> 'Ghi nhận thời gian ra về'
        ]);
        $timesheet = $this->getTimesheetById($timesheetId);
        return $timesheet->update($validated);
    }

    public function export($userId)
    {
        $timesheets = $timesheets = Timesheet::filter()->where('user_id', $userId)->get();
        $user = User::find($userId);
        $user->load('department');
        $data = [[$user->name, $user->department->name, $user->role, $user->email]];
        $headings = [
                    ['Month: ' . request()->input('month'), 'Year: ' . request()->input('year')],
                    ['Name', 'Department', 'Role', 'Email'],
                    ['', '', '', ''],
                ];
        $startPointer = Carbon::create(request()->input('year'), request()->input('month'), 1);
        $endOfTheMonth = $startPointer->copy()->endOfMonth();

        // generate data and header
        $timesheetMap = $timesheets->keyBy('date');
        while ($startPointer->lte($endOfTheMonth)) {
            $formattedDate = $startPointer->format('Y-m-d');
            array_push($headings[1], $startPointer->format('d'));
            array_push($headings[2], $startPointer->format('D'));
            if (isset($timesheetMap[$formattedDate])) {
                $timesheet = $timesheetMap[$formattedDate];
                if ($timesheet->check_out_time == null) {
                    $pushingData = 'X';
                } else {
                    $checkinTime = Carbon::createFromTimeString($timesheet->check_in_time);
                    $checkOutTime = Carbon::createFromTimeString($timesheet->check_out_time);
                    $pushingData = $checkinTime->diffInHours($checkOutTime) - 1 > 0 ? $checkinTime->diffInHours($checkOutTime) - 1 : 0;
                }
                array_push($data[0], $pushingData);

            } else {
                array_push($data[0], 'X');
            }
            $startPointer->addDay();
        }
        return Excel::download(new TimesheetExport($headings, $data), 'timesheet.xlsx');
      }
}
