<div class=" py-5" style="min-height: 80vh;">

<div class="d-flex flex-grow-1 flex-column"></div>
<div class="accordion mt-3 w-100" id="accordionExample1" dir="rtl">
	<div class="accordion-item rounded-5 overflow-hidden border">
		<h2 class="accordion-header" id="headingOne">
			<button class="accordion-button rounded-0" type="button" data-bs-toggle="collapse"
				data-bs-target="#collapseOne1" aria-expanded="true" aria-controls="collapseOne1">
				<div class="d-flex  justify-contetn-center align-items-center flex-nowrap gap-2 ">
					<img src="[@settings->site]assets/img/ehra.png" alt="مدارک احراز" width="35" height="35" class="d-none d-sm-block">
					<span class="font-color small text-nowrap">مدارک احراز هویت | </span> 
					
					<span class="bg-blue px-2 pt-1 rounded-circle text-primary small">2</span>
					<span class="font-color small text-nowrap">مورد در انتظار بررسی</span>
				</div>
			</button>
		</h2>
		<div id="collapseOne1" class="accordion-collapse collapse show" aria-labelledby="headingOne"
			data-bs-parent="#accordionExample1">
			<div class="accordion-body bg-white">
				<div class="table-responsive">
					<table class="table table-striped  text-center align-middle mb-0">
						<thead>
							<tr>
								<th>نام و نام خانوادگی</th>
								<th>زمان ارسال فایل</th>
								<th>مدارک</th>
							</tr>
						</thead>
						<tbody>
							[foreach ([@Objects->docList])]
							<tr>
								<td>[@Item->user]</td>
								<td data-timestamp="UnixTimestamp">[@Item->persianDate]</td>
								<td>
									<button class="btn btn-outline-primary rounded-4 w-100"
										data-bs-toggle="modal" data-bs-target="#modal_view_docs">
										مشاهده مدارک
									</button>
								</td>
							</tr>
							[endforeach]
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal:documents -->
<div class="modal fade " id="modal_view_docs" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<p class="modal-title d-flex align-items-center gap-2 mb-0">
					<img src="[@settings->site]assets/img/ehra.png" alt="cards" width="35" height="35">
					<span>مدارک کاربر: <span class="text-success">بنفشه ابراهیمی</span></span>
				</p>
				<button type="button" class="btn-close ms-auto me-0" data-bs-dismiss="modal"
					aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="d-flex flex-column align-items-center">
					<div class="documentBox bg-light w-100 mb-3" style="max-width: 100%; height: 200px;"></div>

					<select class="form-select mt-2 rounded-5 w-100">
						<option selected>علت وارد شده</option>
						<option value="1">دلیل عمومی</option>
						<option value="2">کیفیت پایین</option>
						<option value="3">عدم وجود برگه</option>
						<option value="4">عدم تطابق کاربر</option>
						<option value="5">عدم داشتن کارت ملی</option>
						<option value="6">عدم مشخص بودن چهره</option>
						<option value="7">تصویر ادیت شده</option>
						<option value="8">برگه ناقص</option>
					</select>

					<input type="text" class="form-control rounded-5 mt-3 text-muted w-100"
						id="reject-file" placeholder="دلیل رد شدن این فایل ...">

					<div class="d-flex w-100 gap-2 mt-3">
						<button class="btn btn-success flex-fill rounded-4 text-white">تایید</button>
						<button class="btn btn-danger flex-fill rounded-4 text-white">عدم تایید</button>
					</div>

					<button class="btn btn-danger rounded-4 w-100 mt-3 text-white">
						<img src="[@settings->site]assets/img/trash.png" alt="trash" width="24" height="24"> حذف فایل
					</button>
				</div>
			</div>
		</div>
	</div>
</div>




<div class="accordion mt-5" id="accordionExample" dir="rtl" data-bs-target="user-management">
  <div class="accordion-item rounded-5 overflow-hidden border">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button rounded-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        <div class="d-flex align-items-center gap-2 flex-nowrap">
          <img src="[@settings->site]assets/img/hesabbanki.png" alt="hesab banki" width="35" height="35" class="d-none d-sm-block">
          <span class="font-color small text-nowrap">حساب‌های بانکی</span>
          <span class="bg-blue px-1 pt-1 rounded-circle text-primary small">2</span>
          <span class="font-color small text-nowrap">مورد انتظار در بررسی</span>
        </div>
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
      <div class="accordion-body bg-white">
        <div class="table-responsive">
          <table class="table table-striped text-center mb-0">
            <thead class="py-3">
              <tr class="align-middle">
                <th>نام و نام خانوادگی</th>
                <th>نام بانک</th>
                <th>شماره شبا</th>
                <th>شماره کارت</th>
                <th class="text-center">عملیات</th>
              </tr>
            </thead>
            <tbody>
              [foreach ([@Objects->Cards])]
              <tr class="align-middle">
                <td>[@Item->user]</td>
                <td>[@Item->BankName]</td>
                <td>[@Item->Shaba]</td>
                <td>[@Item->MaskedCard]</td>
                <td>
                  <div class="d-flex flex-nowrap gap-1">
                    <button class="btn btn-success btn-sm btn-sm-success opacity-green text-green rounded-4 flex-fill w-100">
                      تایید
                    </button>
                    <button class="btn btn-sm btn-danger text-danger opacity-danger rounded-4 flex-fill w-100">
                      عدم تایید
                    </button>
                  </div>
                </td>
              </tr>
              [endforeach]
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>



    <!-- Nav Pills -->
    <ul class="nav nav-pills nav-justified gap-1 pb-3 mt-5" id="financeTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link text-purple active rounded-pill" data-bs-toggle="pill" data-bs-target="#deposits"
                type="button" role="tab">لیست سفارش‌ها</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-purple rounded-pill" data-bs-toggle="pill" data-bs-target="#credits"
                type="button" role="tab"> لیست کاربران</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-purple rounded-pill" data-bs-toggle="pill" data-bs-target="#settlements"
                type="button" role="tab">لیست درخواست‌های تسویه </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- orderList -->
        <div class="tab-pane fade show active" id="deposits" role="tabpanel">
            <div class="table-responsive shadow-sm rounded-3 bg-white p-3">
                <table class="table table-striped table-hover align-middle text-center mb-0 searchable-table">
                    <thead class="table-secondary">
                        <tr>
                            <th class="text-start">
                                <div class="search-wrapper d-flex align-items-center">
                                    <i class="fas fa-search search-icon me-1" data-column="0"
                                        style="cursor:pointer;"></i>
                                    <input type="text" class="search-input form-control form-control-sm"
                                        placeholder="جستجو..." />
                                    <span>شماره سفارش‌ها </span>
                                </div>
                            </th>
                            <th class="text-start"> مشخصات سفارش</th>
                            <th class="text-start">کاربر</th>
                            <th class="text-start"> مبلغ</th>
                            <th class="text-start sortable" data-column="4" style="cursor:pointer">
                                زمان و تاریخ تراکنش <i class="fas fa-sort"></i>
                            </th>
                            <th class="text-start">وضعیت <i class="fas fa-sort"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        [foreach ([@Objects->orderList])]
                        <tr>
                            <td class="text-start fw-semibold">[@Item->numberOrder]
                            </td>
                            <td class="text-start fw-semibold">[@Item->OrderDetails]
                            </td>
                            <td class="text-start fw-semibold"><a href="[@settings->site]manage/[@Item->UserID]/">
                                    [@Item->User]
                                </a></td>
                            <td class="text-start fw-semibold">[@Item->price]</td>
                            <td class="text-start" data-timestamp="[@Item->UnixTimestamp]">[@Item->persianDate]</td>
                            <td class="text-start">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="px-3 py-2 rounded-pill [@Item->StatusColor]">
                                        [@Item->Status]
                                    </span>
                                    <i class="fas fa-ellipsis-v text-secondary ms-2" style="cursor:pointer;"></i>
                                </div>
                        </tr>
                        [endforeach]
                    </tbody>
                </table>
            </div>
        </div>
        <!-- usersList -->
        <div class="tab-pane fade" id="credits" role="tabpanel">
            <div class="table-responsive shadow-sm rounded-3 bg-white p-3">
                <table class="table table-striped table-hover align-middle text-center mb-0 searchable-table">
                    <thead class="table-secondary">
                        <tr>
                            <th class="text-start">
                                <div class="search-wrapper d-flex align-items-center">
                                    <i class="fas fa-search search-icon me-1" data-column="0"
                                        style="cursor:pointer;"></i>
                                    <input type="text" class="search-input form-control form-control-sm"
                                        placeholder="جستجو..." />
                                    <span> کد ملی </span>
                                </div>
                            </th>
                            <th class="text-start">
                                <div class="search-wrapper d-flex align-items-center">
                                    <i class="fas fa-search search-icon me-1" data-column="0"
                                        style="cursor:pointer;"></i>
                                    <input type="text" class="search-input form-control form-control-sm"
                                        placeholder="جستجو..." />
                                    <span>شماره موبایل </span>
                                </div>
                            </th>
                            <th class="text-start">کاربر</th>
                            <th class="text-start">
                                آخرین فعالیت
                            </th>
                            <th class="text-start"> تاریخ ثبت‌نام<i class=" fas fa-sort ms-1"></i></th>
                            <th class="text-start"> وضعیت<i class=" fas fa-sort ms-1"></i></th>

                        </tr>
                    </thead>
                    <tbody>
                        [foreach ([@Objects->userList])]
                        <tr>
                            <td class="text-start fw-semibold">[@Item->nationalCode]</td>
                            <td class="text-start fw-semibold">[@Item->phoneNumber]</td>
                            <td class="text-primary text-start">
                                <a href="[@settings->site]users/[@Item->UserID]/" class="text-decoration-none">
                                    [@Item->User]
                                </a>
                            </td>
                            <td class="text-start fw-semibold">[@Item->lastActivity]</td>
                            <td class="text-start" data-timestamp="[@Item->UnixTimestamp]">[@Item->persianDate]</td>
                            <td class="text-start">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="px-3 py-2 rounded-pill [@Item->StatusColor]">
                                        [@Item->Status]
                                    </span>
                                    <i class="fas fa-ellipsis-v text-secondary ms-2" style="cursor:pointer;"></i>
                                </div>
                            </td>

                        </tr>
                        [endforeach]
                    </tbody>
                </table>
            </div>
        </div>
        <!--requestList-->
        <div class="tab-pane fade" id="settlements" role="tabpanel">
            <div class="table-responsive shadow-sm rounded-3 bg-white p-3">
                <table class="table table-striped table-hover align-middle text-center mb-0 searchable-table">
                    <thead class="table-secondary">
                        <tr>
                            <th class="text-start">
                                <div class="search-wrapper d-flex align-items-center">
                                    <i class="fas fa-search search-icon me-1" data-column="0"
                                        style="cursor:pointer;"></i>
                                    <input type="text" class="search-input form-control form-control-sm"
                                        placeholder="جستجو..." />
                                    <span>شماره درخواست تسویه  </span>
                                </div>
                            </th>
                            <th class="text-start">
                                <div class="search-wrapper d-flex align-items-center">
                                    <i class="fas fa-search search-icon me-1" data-column="0"
                                        style="cursor:pointer;"></i>
                                    <input type="text" class="search-input form-control form-control-sm"
                                        placeholder="جستجو..." />
                                    <span>شماره پیگیری</span>
                                </div>
                            </th>
                            <th class="text-start">کاربر</th>
                            <th class="text-start">مبلغ</th>
                              <th class="text-start sortable" data-column="4" style="cursor:pointer">
                                تاریخ و زمان ثبت نام 
                                +
                                41
                                i class="fas fa-sort"></i>
                            </th>
                                                     <th class="text-start">وضعیت <i class="fas fa-sort"></i></th>


                        </tr>
                    </thead>
                    <tbody>
                        [foreach ([@Objects->requestList])]
                        <tr>
                            <td class="text-start fw-semibold">[@Item->requestCode]</td>
                            <td class="text-start fw-semibold">[@Item->trackingNumber]
                            </td>
                            <td class="text-primary text-start">
                                <a href="[@settings->site]users/[@Item->UserID]/" class="text-decoration-none">
                                    [@Item->User]
                                </a>
                            </td>
                            <td class="text-start fw-bold">[@Item->price]</td>
                            <td class="text-start" data-timestamp="[@Item->UnixTimestamp]">[@Item->persianDate]</td>
                            <td class="text-start">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="px-3 py-2 rounded-pill [@Item->StatusColor]">
                                        [@Item->Status]
                                    </span>
                                    <i class="fas fa-ellipsis-v text-secondary ms-2" style="cursor:pointer;"></i>
                                </div>
                            </td>
                        </tr>
                        [endforeach]
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</div>