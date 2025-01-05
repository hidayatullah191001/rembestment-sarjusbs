@foreach ($budgets as $budget)
    <!-- Modal -->
    <div class="modal fade" id="relocationModal{{ $budget->province_id }}" tabindex="-1" aria-labelledby="relocationModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Relocation Budget {{ $budget->province->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('relocation.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="">From Province</label>
                                    <input type="text" class="form-control form-control-sm" name="from_province"
                                        value="{{ $budget->province->name }}" required readonly>
                                    <input type="text" class="form-control form-control-sm"
                                        name="from_province_value" hidden value="{{ $budget->province_id }}" required
                                        readonly>
                                </div>
                                <div class="mb-3 w-100">
                                    <label for="province_id" class="form-label">Province Name</label><br>
                                    <select class="form-select @error('province_id') is_invalid @enderror"
                                        id="to_province{{ $budget->id }}" name="to_province" required>
                                        <option value="" disabled selected>Pilih Province
                                        </option>
                                        @foreach ($provinces as $province)
                                            @if ($province->id != $budget->province_id)
                                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('province_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Relocation Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control currency-input form-control-sm"
                                            name="amount_relocation" id="amount_relocation{{ $budget->id }}" required>
                                        <input type="hidden" name="amount_relocation_value" id="amount_relocation_value{{ $budget->id }}" >
                                    </div>
                                    @error('amount_relocation')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Description (Opsional)</label>
                                    <input type="text" class="form-control form-control-sm @error('description') is_invalid @enderror"
                                        id="description" name="description">
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
              </form>
            </div>
        </div>
    </div>
@endforeach
