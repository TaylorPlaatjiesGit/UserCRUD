@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Users</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">
            Add User
        </button>
    </div>

    <table class="table table-bordered table-striped table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Surname</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $index => $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->surname }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <button
                            class="btn btn-sm btn-primary btn-edit"
                            data-bs-toggle="modal"
                            data-bs-target="#userModal"
                            data-user='@json($user)'
                            @if($user->id === auth()->id()) disabled @endif
                        >Edit</button>

                        <form method="POST" action="{{ route('users.delete', $user->id) }}" class="modal-content d-inline">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE" />
                            <button
                                class="btn btn-sm btn-danger btn-delete"
                                onclick="return confirm('Are you sure you want to delete this user?')"
                                data-user='@json($user)'
                                type="submit"
                                @if($user->id === auth()->id()) disabled @endif
                            >Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $users->links('pagination::bootstrap-5') }}

    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" id="userForm" action="{{ route('users.store') }}" class="modal-content">
                @csrf

                <input type="hidden" name="_method" value="POST" />

                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="name" class="form-label">First Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-6">
                            <label for="surname" class="form-label">Surname</label>
                            <input type="text" name="surname" class="form-control @error('surname') is-invalid @enderror" value="{{ old('surname') }}" required>
                            @error('surname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-6">
                            <label for="id_number" class="form-label">ID Number</label>
                            <input type="number" name="id_number" class="form-control @error('id_number') is-invalid @enderror" value="{{ old('id_number') }}" required>
                            @error('id_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="mobile_number" class="form-label">Mobile Number</label>
                            <input type="number" name="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror" value="{{ old('mobile_number') }}" required>
                            @error('mobile_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-6">
                            <label for="language_id" class="form-label">Language</label>
                            <select name="language_id" class="form-control @error('language_id') is-invalid @enderror" required>
                                <option disabled {{ old('language_id') ? '' : 'selected' }}>Select an Option</option>
                                @foreach($languages as $language)
                                    <option value="{{ $language->id }}" {{ old('language_id') == $language->id ? 'selected' : '' }}>
                                        {{ $language->language }}
                                    </option>
                                @endforeach
                            </select>
                            @error('language_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="birth_date" class="form-label">Birth Date</label>
                            <input type="date" id="birth_date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date') }}" required>
                            @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="interests" class="form-label">Interests</label>
                        @foreach($interests as $interest)
                            <div class="col-3">
                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input @error('interests.*') is-invalid @enderror"
                                        type="checkbox"
                                        name="interests[]"
                                        id="interest_{{ $interest->id }}"
                                        value="{{ $interest->id }}"
                                        {{ in_array($interest->id, old('interests', [])) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="interest_{{ $interest->id }}">
                                        {{ $interest->interest }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        if ($('.invalid-feedback').length > 0) {
            // Show modal if there are validation errors
            $('#userModal').modal('show');
        }

        $('#userModal').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').remove();
        });

        $('#userModal').on('shown.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const user = button.data('user');

            if (user) {
                $('#userForm').attr('action', `/users/update/${user.id}`);
                $('#userForm').find('input[name="_method"]').val('PUT');

                $('[name="name"]').val(user.name);
                $('[name="surname"]').val(user.surname);
                $('[name="email"]').val(user.email);
                $('[name="id_number"]').val(user.id_number);
                $('[name="mobile_number"]').val(user.mobile_number);
                $('[name="language_id"]').val(user.language_id);
                $('[name="birth_date"]').val(user.birth_date);

                if (user.interests) {
                    user.interests.forEach(obj => {
                        $(`#interest_${obj.id}`).prop('checked', true);
                    });
                }

                $('#userModalLabel').text('Edit User');
                $('#submitBtn').text('Update');
            } else {
                $('#userForm').trigger('reset');
                $('#userForm').attr('action', '{{ route("users.store") }}');
                $('#userForm').find('input[name="_method"]').val('POST');

                $('[name^="interests"]').prop('checked', false);

                $('#userModalLabel').text('Add New User');
                $('#submitBtn').text('Submit');
            }
        });

        // Set max date for birth_date, i.e. only days before today should be selectable.
        const today = new Date();
        today.setDate(today.getDate() - 1);
        const maxDate = today.toISOString().split('T')[0];
        document.getElementById('birth_date').setAttribute('max', maxDate);
    });
</script>
@endpush