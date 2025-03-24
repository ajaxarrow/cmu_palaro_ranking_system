@extends('layouts.admin', ['title' => 'Tally'])



@section('content')

<!-- Start Content-->

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container-fluid">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Users</h4>
        </div>
        <div class="text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecordModal">Add User</button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                    <table
                        id="state-saving-datatable"
                        class="table activate-select dt-responsive nowrap w-100" >
                            <thead>
                                <tr>
                                    <th class="ps-0">Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            {{ $user->role }}
                                            @if($user->role === 'tournament_director' && $user->sport)
                                                ({{ $user->sport->name }})
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">Edit</button>

                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}}">Delete</button>

                                        </td>
                                    </tr>

                                    <!-- Update Modal -->
                                    <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $user->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $user->id }}">Edit User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('users.update', $user->id )}}" method="POST" onsubmit="disableSubmitButton(this)" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label for="name_id" class="form-label">Name</label>
                                                            <input type="text" name="name" class="form-control" id="name_id" value='{{ $user->name }}' placeholder="Enter name" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="email_id" class="form-label">Email</label>
                                                            <input type="email" name="email" class="form-control" id="email_id" value='{{ $user->email }}' placeholder="Enter email" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="role_id" class="form-label">Role</label>
                                                            <select name="role" class="form-control role-select" id="role_id_edit_{{ $user->id }}" required>

                                                                <option value="">Select Role</option>
                                                                <option value="encoder" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                                                <option value="encoder" {{ $user->role == 'encoder' ? 'selected' : '' }}>Encoder</option>
                                                                <option value="tournament_director" {{ $user->role == 'tournament_director' ? 'selected' : '' }}>Tournament Director</option>
                                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3 sport-field" id="sport_field_edit_{{ $user->id }}" style="display: none;">
                                                            <label for="sport_id_edit_{{ $user->id }}" class="form-label">Sport</label>
                                                            <select name="sport_id" class="form-control" id="sport_id_edit_{{ $user->id }}" >
                                                                <option value="">Select Sport</option>
                                                                @foreach($sports as $sport)
                                                                    <option value="{{ $sport->id }}" {{ ($user->sport_id == $sport->id) ? 'selected' : '' }}>{{ $sport->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="hstack gap-2 justify-content-end">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="deleteModal{{ $user->id }}}"  tabindex="-1" aria-labelledby="deleteModal{{ $user->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">Delete Team</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this user?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        <form id="deleteForm{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="disableSubmitButton(this)">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>


                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Record Modal -->
<div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="addRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRecordModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('users.store')}}" method="POST" onsubmit="disableSubmitButton(this)">
                    @csrf
                    <div class="mb-3">
                        <label for="name_id" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="name_id" placeholder="Enter name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email_id" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email_id" placeholder="Enter email" required>
                    </div>
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role</label>
                        <select name="role" class="form-control" id="role_id" required>
                            <option value="">Select Role</option>
                            <option value="user">User</option>
                            <option value="encoder">Encoder</option>
                            <option value="tournament_director">Tournament Director</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3 sport-field" id="sport_field_add" style="display: none;">
                        <label for="sport_id_add" class="form-label">Sport</label>
                        <select name="sport_id" class="form-control" id="sport_id_add">
                            <option value="">Select Sport</option>
                            @foreach($sports as $sport)
                                <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Password with toggle -->
                    <div class="mb-3">
                        <label for="password1_id" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password1" class="form-control" id="password1_id" placeholder="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword1" aria-label="Show/hide password">
                                <i class="ph-duotone fs-24 ph-eye mb-0"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password2_id" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" name="password2" class="form-control" id="password2_id" placeholder="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword2" aria-label="Show/hide password">
                                <i class="ph-duotone fs-24 ph-eye mb-0"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" id='gen_password' class="btn btn-light w-100 mb-4">Generate Password</button>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection


@section('script-bottom')


<script>
    document.addEventListener("DOMContentLoaded", function () {
        function toggleSportField(selectElement, sportFieldId) {
            let sportField = document.getElementById(sportFieldId);
            if (selectElement.value === "tournament_director") {
                sportField.style.display = "block";
            } else {
                sportField.style.display = "none";
            }
        }

        // For Add User Modal
        let roleSelectAdd = document.getElementById("role_id");
        let sportFieldAdd = document.getElementById("sport_field_add");
        roleSelectAdd.addEventListener("change", function () {
            toggleSportField(this, "sport_field_add");
        });

        // For Edit User Modals
        document.querySelectorAll(".role-select").forEach((select) => {
            const userId = select.id.split('_')[3]; // Get user ID from select ID
            const sportFieldId = `sport_field_edit_${userId}`;
            
            // Add change event listener
            select.addEventListener("change", function () {
                toggleSportField(this, sportFieldId);
            });

            // Initialize visibility based on current role
            toggleSportField(select, sportFieldId);
        });

        // Password toggle functionality
        const togglePassword1 = document.getElementById('togglePassword1');
        const password1 = document.getElementById('password1_id');

        if (togglePassword1) {
            togglePassword1.addEventListener('click', function() {
                const type = password1.getAttribute('type') === 'password' ? 'text' : 'password';
                password1.setAttribute('type', type);
                this.querySelector('i').classList.toggle('ph-eye');
                this.querySelector('i').classList.toggle('ph-eye-slash');
            });
        }

        const togglePassword2 = document.getElementById('togglePassword2');
        const password2 = document.getElementById('password2_id');

        if (togglePassword2) {
            togglePassword2.addEventListener('click', function() {
                const type = password2.getAttribute('type') === 'password' ? 'text' : 'password';
                password2.setAttribute('type', type);
                this.querySelector('i').classList.toggle('ph-eye');
                this.querySelector('i').classList.toggle('ph-eye-slash');
            });
        }

        // Password generator
        document.getElementById('gen_password').addEventListener('click', function() {
            const password1 = document.getElementById('password1_id');
            const password2 = document.getElementById('password2_id');
            const randomPassword = Math.random().toString(36).slice(-8);
            password1.value = randomPassword;
            password2.value = randomPassword;
        });

        // Form submission handling
        function disableSubmitButton(form) {
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerText = 'Submitting...';
        }
    });
</script>

@endsection
