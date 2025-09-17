@extends('layouts/contentNavbarLayout')

@section('title', 'Client')

@section('content')

    @if(session('success'))
      <div class="alert alert-success" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0">Client List</h4>
            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addClientModal">+ Add Client</a> 
        </div>

         <div class="card-header pt-0 pb-0">
            <form action="" method="get">
                <div class="row">
                    <div class="col-md-6"></div>
                        <div class="col-md-6">  
                            <div class="d-flex justify-content-end align-items-center">
                            <div class="form-group me-2 mb-0">
                                <input type="search" class="form-control form-control-sm" name="keyword" id="keyword" value="{{ request()->input('keyword') }}" placeholder="Search something...">
                            </div>
                            <div class="form-group mb-0">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class=" tf-icons ri-filter-3-line"></i>
                                    </button>
                                    <a href="{{ url()->current() }}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Clear filter">
                                        <i class=" tf-icons ri-close-line"></i>
                                    </a>                            
                                </div>
                            </div>
                            </div>
                        </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Actions</th>
                            <th>Training Access</th>
                        </tr>
                    </thead>
                    <tbody id="clientTableBody">
                        @foreach($client as $item)
                            <tr>
                                <td>{{ ucwords($item->name) }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->mobile_no }}</td>
                                <td>{{ ucwords($item->address) }}</td>
                                <td>
                                    <div class="form-check form-switch" data-bs-toggle="tooltip" title="Toggle status">
                                        <input class="form-check-input ms-auto" type="checkbox" id="customSwitch{{$item->id}}"
                                        {{ $item->status ? 'checked' : ''}} onclick="statusToggle('{{route('admin.client.status', $item->id)}}', this)">
                                        <label class="form-check-label" for="customSwitch{{$item->id}}"></label>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info slotBtn" data-id="{{ $item->id}}"
                                            data-bs-toggle="modal"  data-bs-target="#slotModal">Slot                                                                               
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-dark editClientBtn" data-id="{{ $item->id }}" data-bs-toggle="tooltip"  title="Edit">
                                        <i class="ri-pencil-line"></i></button>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-danger" onclick="deleteClient({{ $item->id }})"     
                                        data-bs-toggle="tooltip" title="Delete">
                                        <i class="ri-delete-bin-6-line"></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="form-check form-switch" data-bs-toggle="tooltip" title="Toggle status">
                                        <input class="form-check-input ms-auto" type="checkbox" id="customSwitch1{{$item->id}}"
                                        {{ $item->training_status ? 'checked' : ''}} onclick="statusToggle('{{route('admin.client.trainingStatus', $item->id)}}', this)">
                                        <label class="form-check-label" for="customSwitch{{$item->id}}"></label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $client->links() }}
            </div>

            {{-- add client --}}
            <div class="modal fade" id="addClientModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="addClientForm" autocomplete="off">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Client</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="formErrors" class="alert alert-danger d-none"></div>
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control"/>
                                    <span class="text-danger error-text name_error"></span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" autocomplete="off"/>
                                    <span class="text-danger error-text email_error"></span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mobile</label>
                                    <input type="text" name="mobile_no" class="form-control"/>
                                    <span class="text-danger error-text mobile_no_error"></span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control"></textarea>
                                    <span class="text-danger error-text address_error"></span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" autocomplete="new-password"/>
                                    <span class="text-danger error-text password_error"></span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save Client</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- edit client --}}
            <div class="modal fade" id="editClientModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="updateClientForm" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="edit_client_id">

                            <div class="modal-header">
                                <h5 class="modal-title">Edit Client</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Name</label>
                                    <input type="text" name="name" id="edit_name" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" id="edit_email" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label>Mobile</label>
                                    <input type="text" name="mobile_no" id="edit_mobile_no" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label>Address</label>
                                    <input type="text" name="address" id="edit_address" class="form-control">
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- add slot --}}
            <div class="modal fade" id="slotModal" tabindex="-1" aria-labelledby="slotModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Manage Slots</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form id="slotForm">
                            @csrf
                            <input type="hidden" name="client_id" id="client_id">
                            <div class="modal-body">
                                <table class="table table-bordered" id="slotTable">
                                    <thead>
                                        <tr>
                                            <th>Day</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Slot</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Rows will be appended dynamically -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer d-flex justify-content-end flex-column align-items-end">
                                <button type="button" class="btn btn-sm btn-success mb-2" id="addSlotRow">+ Add More</button>

                                <div>
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Slots</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
<script>
    // add client
    $(document).ready(function () {
        $('#addClientForm').submit(function (e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('admin.client.store') }}",
                method: "POST",
                data: formData,
                success: function (response) {
                    if (response.success) {
                        $('#addClientModal').modal('hide');
                        $('#addClientForm')[0].reset();

                        // Reload page to see changes
                        window.location.href = "{{ route('admin.client.list') }}";
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            $('.' + key + '_error').text(value[0]);
                        });
                    }
                }
            });
        });
    });

    // edit client   
    $(document).on('click', '.editClientBtn', function () {
        let clientId = $(this).data('id');

        // Generate base route from Laravel
        let url = "{{ route('admin.client.edit', ':id') }}";
        url = url.replace(':id', clientId); // replace placeholder with actual id

        $.ajax({
            url: url,
            type: 'GET',
            success: function (res) {
                $('#edit_client_id').val(res.id);
                $('#edit_name').val(res.name);
                $('#edit_email').val(res.email);
                $('#edit_mobile_no').val(res.mobile_no);
                $('#edit_address').val(res.address);

                $('#editClientModal').modal('show');
            }
        });
    });


    // Submit Edit Form
    $('#updateClientForm').on('submit', function (e) {
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: "{{route('admin.client.update')}}",  
            type: 'POST',
            data: formData,
            success: function (data) {
                $('#editClientModal').modal('hide');
                toastFire('success','Client updated successfully');
                location.reload();
            },
            error: function (err) {
                console.error(err);
                toastFire('error','Something went wrong!');
            }
        });
    });


    function deleteClient(userId) {
        Swal.fire({
            icon: 'warning',
            title: "Are you sure you want to delete this?",
            text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Delete",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.client.delete')}}",
                    type: 'POST',
                    data: {
                        "id": userId,
                        "_token": '{{ csrf_token() }}',
                    },
                    success: function (data){
                        if (data.status != 200) {
                            toastFire('error', data.message);
                        } else {
                            toastFire('success', data.message);
                            location.reload();
                        }
                    }
                });
            }
        });
    }

    // add slot date
    let allDays = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
    const slotGetUrl = "{{ route('admin.client.getSlotdate', ':id') }}";
    const slotSaveUrl = "{{ route('admin.client.saveSlotdate') }}";

    $(document).on("click", ".slotBtn", function () {
        let clientId = $(this).data("id");
        $("#client_id").val(clientId);
        $("#slotTable tbody").empty();

        let url = slotGetUrl.replace(':id', clientId);
        $.get(url, function (res) {
            let slots = res.slots;

            if (slots.length > 0) {
                slots.forEach((s, i) => {
                    addRow(s.day, s.slot, s.start_time, s.end_time, i === 0); //first row -> no X
                });
            } else {
                // always open with 1 row, no X
                addRow('', '', '', '', true);
            }
        });
    });

    // Add Row
    function addRow(day = '', slot = '', start_time = '', end_time = '', isFirst = false) {
        if ($("#slotTable tbody tr").length >= 7) {
            toastFire("warning", "You can add up to 7 slots only");
            return;
        }

        let usedDays = $("#slotTable tbody select").map(function(){ return $(this).val(); }).get();
        let availableDays = allDays.filter(d => !usedDays.includes(d));

        function ucwords(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        let options =  `<option value="">Please select day</option>`;
        // options += availableDays.map(d =>
        //     `<option value="${d}" ${d === day ? 'selected' : ''}>${ucwords(d)}</option>`
        // ).join('');

        options += allDays.map(d =>
            `<option value="${d}" ${d === day ? 'selected' : ''}>${ucwords(d)}</option>`
        ).join('');

        // If day already saved in DB but not in availableDays, add it back
        if(day && !options.includes(`value="${day}"`)){
            options += `<option value="${day}" selected>${ucwords(day)}</option>`;
        }

        let row = `
        <tr>
            <td>
                <select name="day[]" class="form-control">${options}</select>
                <span class="text-danger error-day"></span>
            </td>
            <td>
                <input type="time" name="start_time[]" class="form-control" value="${start_time}">
                <span class="text-danger error-start_time"></span>
            </td>
            <td>
                <input type="time" name="end_time[]" class="form-control" value="${end_time}">
                <span class="text-danger error-end_time"></span>    
            </td>
            <td>
                <input type="number" name="slot[]" class="form-control" value="${slot}">
                <span class="text-danger error-slot"></span>
            </td>
            <td>
                ${isFirst ? '' : '<button type="button" class="btn btn-danger btn-sm removeRow">X</button>'}
            </td>
        </tr>`;
        $("#slotTable tbody").append(row);
    }

    // Remove row
    $(document).on("click", ".removeRow", function () {
        $(this).closest("tr").remove();
    });

    // Add new row
    $(document).on("click", "#addSlotRow", function () {
        addRow();
    });

    // Save slots
    $("#slotForm").submit(function(e){
        e.preventDefault();
        $(".error-day").text("");
        $(".error-slot").text("");

        let formData = $(this).serialize();
        $.ajax({
            url: slotSaveUrl,
            type: "POST",
            data: formData,
            success: function(data){
                if(data.success){
                    $("#slotModal").modal("hide");
                    toastFire("success", "Slots saved successfully");
                    location.reload();
                }
            },
            // error: function(xhr){
            //     if(xhr.status === 422){
            //         let errors = xhr.responseJSON.errors;

            //         // day errors
            //         if(errors.day){
            //             $("#slotTable tbody tr").each(function(){
            //                 let selectedDay = $(this).find("select").val();
            //                 if(errors.day.some(e => e.includes(selectedDay))){
            //                     $(this).find(".error-day").text(errors.day[0]);
            //                 }
            //             });
            //         }

            //         // slot required errors
            //         if(errors.slot){
            //             $("#slotTable tbody tr").each(function(i){
            //                 if(errors.slot[i]){
            //                     $(this).find(".error-slot").text(errors.slot[i]);
            //                 }
            //             });
            //         }
            //     } else {
            //         toastFire("error", "Something went wrong!");
            //     }
            // }

            error: function(xhr){
                if(xhr.status === 422){
                    let errors = xhr.responseJSON.errors;

                    // day errors
                    if(errors.day){
                        $("#slotTable tbody tr").each(function(i){
                            if(errors.day[i]){
                                $(this).find(".error-day").text(errors.day[i]);
                            }
                        });
                    }

                    // slot errors
                    if(errors.slot){
                        $("#slotTable tbody tr").each(function(i){
                            if(errors.slot[i]){
                                $(this).find(".error-slot").text(errors.slot[i]);
                            }
                        });
                    }

                    // start_time errors
                    if(errors.start_time){
                        $("#slotTable tbody tr").each(function(i){
                            if(errors.start_time[i]){
                                $(this).find(".error-start_time").text(errors.start_time[i]);
                            }
                        });
                    }

                    // end_time errors
                    if(errors.end_time){
                        $("#slotTable tbody tr").each(function(i){
                            if(errors.end_time[i]){
                                $(this).find(".error-end_time").text(errors.end_time[i]);
                            }
                        });
                    }

                    // time overlap errors (we put them in "time" key in controller)
                    if(errors.time){
                        $("#slotTable tbody tr").each(function(i){
                            if(errors.time[i]){
                                $(this).find(".error-start_time").text(errors.time[i]);
                                $(this).find(".error-end_time").text(errors.time[i]);
                            }
                        });
                    }

                } else {
                    toastFire("error", "Something went wrong!");
                }
            }

        });
    });


</script>
@endsection