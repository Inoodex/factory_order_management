@extends('admin.layouts.master')

@section('title', 'Generate Salaries')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Generate Salaries</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.salaries.index') }}" class="btn btn-outline-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                Back to Salaries
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.salaries.generate') }}" method="GET" class="flex gap-4 items-center">
                <div>
                    <label for="month" class="block text-sm font-medium">Select Month</label>
                    <input type="month" name="month" value="{{ $month }}" class="form-input" required />
                </div>
                <div class="mt-6">
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>

        <form action="{{ route('admin.salaries.bulk-store') }}" method="POST">
            @csrf
            <input type="hidden" name="month" value="{{ $month }}" />
            <div class="mb-4 flex justify-end">
                <button type="button" id="addCustomSalaryRow" class="btn btn-outline-primary">Add Employee</button>
            </div>
            <div class="datatable">
                <div class="overflow-x-auto">
                    <table class="table-hover w-full table-auto">
                        <thead>
                            <tr>
                                <th class="w-10">
                                    <input type="checkbox" id="selectAll" class="form-checkbox" checked />
                                </th>
                                <th class="w-16 text-center">SL</th>
                                <th>Employee Name</th>
                                <th>Basic Salary</th>
                                <th>Bonus</th>
                                <th>Deduction</th>
                                <th>Net Salary</th>
                                <th>Savings Account</th>
                                <th class="w-10 text-center"></th>
                            </tr>
                        </thead>
                        <tbody id="salary-rows">
                            @forelse($salaries as $index => $salary)
                                <tr class="salary-row group transition-all duration-200" data-index="{{ $index }}">
                                    <td>
                                        <input type="checkbox" class="form-checkbox row-checkbox" checked />
                                    </td>
                                    <td class="sl-number text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <input type="hidden" name="salaries[{{ $index }}][id]" value="{{ $salary['id'] ?? '' }}" />
                                        <input type="hidden" name="salaries[{{ $index }}][user_id]" value="{{ $salary['user_id'] ?? '' }}" />
                                        <input type="hidden" name="salaries[{{ $index }}][designation]" value="{{ $salary['designation'] ?? 'Staff' }}" />
                                        <input type="text" name="salaries[{{ $index }}][employee_name]"
                                            value="{{ $salary['name'] ?? $salary['employee_name'] ?? '' }}" class="form-input" required />
                                    </td>
                                    <td>
                                        <input type="number" step="0.01"
                                            name="salaries[{{ $index }}][basic_salary]"
                                            value="{{ $salary['basic_salary'] }}" class="form-input w-32 calc-input" required />
                                    </td>
                                    <td>
                                        <input type="number" step="0.01"
                                            name="salaries[{{ $index }}][bonus]"
                                            value="{{ $salary['bonus'] ?? 0 }}" class="form-input w-24 calc-input" />
                                    </td>
                                    <td>
                                        <input type="number" step="0.01"
                                            name="salaries[{{ $index }}][deduction]"
                                            value="{{ $salary['deduction'] ?? 0 }}" class="form-input w-24 calc-input" />
                                    </td>
                                    <td class="font-bold text-primary">
                                        <span class="net-salary-display">{{ number_format($salary['net_salary'] ?? $salary['basic_salary'], 2) }}</span>
                                    </td>
                                    <td>
                                        <input type="text" name="salaries[{{ $index }}][account_number]"
                                            value="{{ $salary['account_number'] ?? '' }}" class="form-input w-40" />
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="text-danger remove-row opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No employees found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if (count($salaries) > 0)
                <div class="mt-4 flex justify-end">
                    <!-- <div class="flex gap-2">
                        <a href="{{ route('admin.salaries.export-pdf', ['month' => $month]) }}"
                            class="btn btn-outline-danger gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14,2 14,8 20,8" />
                                <line x1="16" y1="13" x2="8" y2="13" />
                                <line x1="16" y1="17" x2="8" y2="17" />
                                <polyline points="10,9 9,9 8,9" />
                            </svg>
                            Export to PDF
                        </a>
                    </div> -->
                    <button type="submit" class="btn btn-success">Save All Salaries</button>
                </div>
            @endif
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const salaryRowsBody = document.getElementById('salary-rows');
            const addCustomSalaryRowBtn = document.getElementById('addCustomSalaryRow');
            const selectAllBtn = document.getElementById('selectAll');
            let salaryRowIndex = {{ count($salaries) }};

            // Function to update SL numbers
            function updateSL() {
                const rows = document.querySelectorAll('.salary-row');
                rows.forEach((row, index) => {
                    const slCell = row.querySelector('.sl-number');
                    if (slCell) slCell.textContent = index + 1;
                });
            }

            // Function to handle row checkbox state
            function handleRowState(row) {
                const checkbox = row.querySelector('.row-checkbox');
                const inputs = row.querySelectorAll('input:not(.row-checkbox)');
                
                if (checkbox.checked) {
                    row.classList.remove('opacity-40', 'grayscale-[0.5]');
                    inputs.forEach(input => input.disabled = false);
                } else {
                    row.classList.add('opacity-40', 'grayscale-[0.5]');
                    inputs.forEach(input => input.disabled = true);
                }
            }

            // Select All logic
            selectAllBtn.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.row-checkbox');
                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                    handleRowState(cb.closest('.salary-row'));
                });
            });

            // Row checkbox event delegation
            salaryRowsBody.addEventListener('change', function(e) {
                if (e.target.classList.contains('row-checkbox')) {
                    handleRowState(e.target.closest('.salary-row'));
                }
            });

            // Function to calculate net salary for a row
            function calculateRowNet(row) {
                const basic = parseFloat(row.querySelector('input[name*="[basic_salary]"]').value) || 0;
                const bonus = parseFloat(row.querySelector('input[name*="[bonus]"]').value) || 0;
                const deduction = parseFloat(row.querySelector('input[name*="[deduction]"]').value) || 0;
                const net = basic + bonus - deduction;
                
                const display = row.querySelector('.net-salary-display');
                if (display) {
                    display.textContent = net.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
            }

            // Calculation event delegation
            salaryRowsBody.addEventListener('input', function(e) {
                if (e.target.classList.contains('calc-input')) {
                    calculateRowNet(e.target.closest('.salary-row'));
                }
            });

            addCustomSalaryRowBtn.addEventListener('click', function() {
                const index = salaryRowIndex++;
                const tr = document.createElement('tr');
                tr.className = 'salary-row group transition-all duration-200';
                tr.dataset.index = index;
                tr.innerHTML = `
                    <td>
                        <input type="checkbox" class="form-checkbox row-checkbox" checked />
                    </td>
                    <td class="sl-number text-center"></td>
                    <td>
                        <input type="hidden" name="salaries[${index}][id]" value="" />
                        <input type="hidden" name="salaries[${index}][user_id]" value="" />
                        <input type="hidden" name="salaries[${index}][designation]" value="Staff" />
                        <input type="text" name="salaries[${index}][employee_name]" class="form-input" placeholder="Employee name" required />
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" name="salaries[${index}][basic_salary]" value="0" class="form-input w-32 calc-input" required />
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" name="salaries[${index}][bonus]" value="0" class="form-input w-24 calc-input" />
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" name="salaries[${index}][deduction]" value="0" class="form-input w-24 calc-input" />
                    </td>
                    <td class="font-bold text-primary">
                        <span class="net-salary-display">0.00</span>
                    </td>
                    <td>
                        <input type="text" name="salaries[${index}][account_number]" class="form-input w-40" placeholder="Account no" />
                    </td>
                    <td class="text-center">
                        <button type="button" class="text-danger remove-row opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </td>
                `;

                salaryRowsBody.appendChild(tr);
                updateSL();
            });

            // Handle row removal
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    e.target.closest('.salary-row').remove();
                    updateSL();
                }
            });
        });
    </script>
@endsection
