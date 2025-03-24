@foreach ($anggota as $data)
                            @if (in_array($data->status_bendahara, ['Diterima', 'Ditolak']))
                                <tr>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input checkbox-item"
                                                id="checkbox{{ $loop->index }}" data-id="{{ $data->id }}">
                                            <label class="custom-control-label" for="checkbox{{ $loop->index }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->tempat_lahir }}</td>
                                    <td>{{ $data->tgl_lahir }}</td>
                                    <td>{{ $data->nik }}</td>
                                    <td>{{ $data->email_kantor }}</td>
                                    <td>{{ $data->no_handphone }}</td>
                                    <td>{{ $data->alamat_domisili }}</td>
                                    <td>{{ $data->alamat_ktp }}</td>
                                    <td>
                                        @if ($data->status_ketua == 'Diterima')
                                            <span class="badge badge-border-success">diterima ketua</span>
                                        @elseif($data->status_ketua == 'Ditolak')
                                            <span class="badge badge-border-danger">ditolak ketua</span>
                                        @else
                                            <span class="badge badge-border-warning">Pengajuan</span>
                                        @endif
                                    </td>
                                <td class="action-icons">
                                    <a href="#" class="action-icons" data-id="{{ $data->id }}" onclick="viewDetail(this)">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <i class="fas fa-edit edit" data-id="{{ $data->id }}" onclick="openEditModal(this)"></i>
                                    <i class="fas fa-trash delete" data-id="{{ $data->id }}" onclick="deleteData(this)"></i>
                                </td>

                </tr>
                @endif
                @endforeach