@extends('layouts.content')
@section('main-content')
<form action="{{ route('saleForm') }}" method="post">
    @csrf
    <div class="col-md-12 bg-white cont">
        <div class="col-md-12 head rounded"><p class="content-header"><i class='bx bx-notepad bx-sm'></i> Satış Oluştur</p></div>
        <div class="form-content">
            <div class="row m-0 p-0">
                <div class="col-md-6 m-0 form-content-row">
                    <p class="p-0 mb-1"><strong>Proje Adı</strong></p>
                    <input type="text" class="inputs" name="project_name">
                </div>

                <div class="col-md-6 m-0 form-content-row">
                    <p class="p-0 mb-1"><strong>Firma Yetkilisi</strong></p>
                    <select class="js-example-basic-single" name="company_person" style="width: 100%;padding: 10px" id="companyPerson">
                        <option value="0">Seçiniz</option>
                        @foreach ($companies as $row)
                            <option value="{{ $row['id'] }}">{{ $row['authorized'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div class="col-md-6">
                    <div class="row ">
                        <div class="col-md-6 m-0  form-content-row">
                            <p class="p-0 mb-1"><strong>Ödeme Yöntemi <small class="text-danger">(zorunlu)</small></strong></p>
                            <select class="js-example-basic-single form-control" name="payment_type" style="width: 100%;padding: 10px">
                                @foreach ($methods as $method)
                                    <option value="{{ $method['id'] }}">{{ $method['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 m-0 form-content-row ">
                            <p class="p-0 mb-1"><strong>Depo <small class="text-danger">(zorunlu)</small></strong></p>
                            <select class="js-example-basic-single" name="storage" style="width: 100%;padding: 10px">
                                @foreach ($departments as $row)
                                    <option value="{{ $row["id"] }}">{{ $row["department"] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row ">
                        <div class="col-md-6 m-0 form-content-row ">
                            <p class="p-0 mb-1"><strong>Firma <small class="text-danger">(zorunlu)</small></strong></p>
                            <input type="text" class="inputs" name="bill" id="form-company" required>
                        </div>
                        <div class="col-md-6 m-0 form-content-row ">
                            <p class="p-0 mb-1"><strong>Mail</strong></p>
                            <input type="email" class="inputs" name="mail" id="form-mail">
                        </div>
                    </div>
                </div>
                <div class="row m-0 p-0">
                    <div class="col-md-6">
                        <div class="row ">

                            <div class="col-md-6 m-0  form-content-row">
                                <p class="p-0 mb-1"><strong>Vade <small class="text-danger">(zorunlu)</small></strong></p>
                                <input type="number" class="inputs" name="expiry" required>
                            </div>
                            <div class="col-md-6 m-0  form-content-row">
                                <p class="p-0 mb-1"><strong>Para Birimi <small class="text-danger">(zorunlu)</small></strong></p>
                                <select class="js-example-basic-single currency" name="currency" style="width: 100%;padding: 10px">
                                    @foreach ($currency as $row)
                                        <option value="{{ $row['id'] }}">{{ $row['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
   
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row ">
                            <div class="col-md-12 m-0 form-content-row ">
                                <p class="p-0 mb-1"><strong>Sevk Adresi</strong></p>
                                <textarea class="areas" name="address" id="form-address" cols="1" rows="1" style="height:30px"></textarea>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="row m-0 p-0">
                    <div class="col-md-6 price-difference-div">
                        <div class="col-md-12">
                            <p class="p-0 mb-1"><strong>Fiyat Farkı</strong></p>
                            <select name="price_diffrence" class="inputs priceDiff p-0">
                                <option value="0">Yok</option>
                                <option value="1">Var</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 sas-div" style="display: none">
                        <div class="col-md-12">
                            <p class="p-0 mb-1"><strong>SAS No</strong></p>
                            <input type="text" name="sas_no" class="inputs sasInput">
                        </div>
                    </div>
                    <div class="col-md-6 sas-div">
                        <div class="col-md-12">
                            <p class="p-0 mb-1"><strong>Süreci Devam Eden Form ( <span style="letter-spacing: 3px">SDF</span>)</strong></p>
                            <select name="process_ongoing" class="inputs p-0">
                                <option value="0">Hayır</option>
                                <option value="1">Evet</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
                
                
            </div>
        </div>
        <div class="col-md-12 head rounded mt-5" style="padding-left: 20px"><p class="content-header"><i class='bx bx-layer bx-sm' ></i> Ürün Bilgileri</p></div>
        <div class="form-content">
            <div class="row m-0 p-0">
                <div class="col-md-6 m-0 form-content-row">
                    <p class="p-0 mb-1"><strong>Ürün Ara</strong></p>
                    <select class="js-example-basic-single" id="getProductsSelectSale" style="width: 100%;padding: 10px">
                        @foreach ($products as $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr>
            <div class="col-md-12 m-0 p-2 table-responsive">
                <table class="table ">
                    <thead>
                      <tr>
                        <th scope="col" class="text-center" width="2%">#</th>
                        <th scope="col" width="26%">Ürün</th>
                        <th scope="col" width="5%">Adet</th>
                        <th scope="col" width="6%">Alış</th>
                        <th scope="col" width="6%">Satış</th>
                        <th scope="col" class="diffrence" width="6%" style="display: none;">Fark (Adet Başına)</th>
                        <th scope="col" width="5%">Vergi</th>
                        <th scope="col" width="8%">Tedarikçi</th>
                        <th scope="col" class="text-center" width="4%">Onay</th>
                      </tr>
                    </thead>
                    <tbody>
                        @for ($i = 1; $i < 51; $i++)
                        <tr class="rows" style="@if ($i >= 2) {{ 'display:none' }} @endif">
                            <th scope="row" class="text-center">{{ $i }}</th>
                            <td>
                                <div style="display:flex;justify-content: center;align-items: center">
                                    <i class="bx bx-trash saleTrash" style="font-size:16px;padding-right: 5px;cursor:pointer;"></i>
                                    <input type="text" class="inputs product" name="product[]">
                                </div>

                            </td>
                            <td>
                                <div style="display:flex;justify-content: center;align-items: center">
                                    <input type="number" class="inputs count stock" name="count[]">
                                    <span style="padding-left: 3px;color: gray;display:none">
                                        <span>(</span><span class="stock"></span><span>)</span>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <input type="number" class="inputs purchasePrice" name="purchasePrice[]" step="0.01">
                            </td>
                            <td>
                                <input type="number" class="inputs salePrice" name="salePrice[]" step="0.01">
                            </td>
                            <td class="diffrence" style="display: none;">
                                <input type="number" class="inputs" name="diffrence[]" step="0.01">
                            </td>
                            <td>
                                <select class="js-example-basic-single tax" name="tax[]" style="width: 100%;">
                                    <option value="18">18</option>
                                    <option value="8">8</option>
                                    <option value="1">1</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="inputs" name="supplier[]">
                            </td>
                            <td>
                                <button type="button" class="butonSale w-100" style="width: 50px;padding: 5px"><i class='bx bx-time-five'></i></button>
                            </td>
                          </tr>
                        @endfor
                    </tbody>
                  </table>
                  <br><br>
                  <div class="row">
                    <div class="col-md-6 mt-3">
                        <textarea name="comment" class="areas p-2" id="" cols="5" rows="4" placeholder="Açıklama"></textarea>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <thead>
                                      <tr>
                                        <th scope="col">Satışlar Toplam</th>
                                        <th style="font-weight: normal;text-align: center;" class="purchaseTotal"></th>
                                      </tr>
                                      <tr>
                                        <th scope="col">Vergi Toplam</th>
                                        <th style="font-weight: normal;text-align: center;" class="taxTotal"></th>
                                      </tr>
                                      <tr>
                                        <th scope="col">Genel Toplam</th>
                                        <th style="font-weight: normal;text-align: center;" class="overallTotal"></th>
                                      </tr>
                                    </thead>
                                  </table>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-4 m-0 form-content-row p-0">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="p-0 mb-1"><strong>Form Sahibi</strong></p>
                            <select class="js-example-basic-single" name="form_owner" style="width: 100%;padding: 10px" required>
                                <option value="">Seçiniz</option>
                                <option value="{{ session()->get('id') }}">{{ session()->get('nameSurname') }}</option>
                                @foreach ($users as $row)
                                    <option value="{{ $row["id"] }}">{{ $row["name"] }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-6">
                            <p class="p-0 mb-1"><strong>Yönetici</strong></p>
                            <select class="js-example-basic-single" name="awaiting_manager" style="width: 100%;padding: 10px">
                                @foreach ($managers as $row)
                                    <option value="{{ $row["id"] }}">{{ $row["name"] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    </div>
                  <div class="col-md-12 m-0 p-0">
                    <div class="col-md-12 mt-3">
                        <button class="sendButton">Talep Oluştur</button>
                      </div>
                  </div>
            </div>
        </div>
    </div>
</form>

@endsection