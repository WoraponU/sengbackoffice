<template>
    <div id="modalAddTruck" class="modal modal-fixed-footer">
        <form action="/backoffice/truck" method="POST">
            <input type="hidden" name="_token" v-model="csrfToken">
            <div class="modal-content">
                <h4>ข้อมูลรถบรรทุก</h4>
                <div class="divider"></div>

                <div class="row mt25">
                    <div class="col s12 m12 l12 center-align">
                        <img class="responsive-img" :src="photoPreview" alt="photo">
                    </div>
                </div>
                <div class="row">
                    <div class="col offset-s1 offset-m1 offset-l1 s11 m11 l11 center-align">
                        <input @change="onImageChange($event)" type="file" accept="image/*" readonly>
                        <input name="photo" type="hidden" :value="photoPreview">
                    </div>
                </div>

                <div class="section">
                    <h5>ข้อมูลทั่วไป</h5>
                    <div class="row">
                        <div class="input-field col s12 m12 l6">
                            <input name="licensePlate" id="licensePlate" type="text" class="validate" required>
                            <label for="licensePlate">เลขทะเบียน <span class="icon-star">*</span></label>
                        </div>
                        <div class="input-field col s12 m12 l6">
                            <input name="owner" id="owner" type="text" class="validate">
                            <label for="owner">เจ้าของ <span class="icon-star">*</span></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 m12 l6">
                            <input name="registerDate" id="registerDate" type="date" class="datepicker">
                            <label for="registerDate">วันที่จดทะเบียน</label>
                        </div>
                        <div class="input-field col s12 m12 l6">
                            <input name="annualTaxDate" id="annualTaxDate" type="date" class="datepicker">
                            <label for="annualTaxDate">วันที่ต่อทะเบียน</label>
                        </div>
                    </div>
                </div>
               
                <div class="section">  
                    <h5>ข้อมูลเชื้อเพลิง</h5>                                  
                    <div class="row">
                        <div class="input-field col s12 m12 l6">
                            <input name="gasoline" id="gasoline" type="text" class="validate" required>
                            <label for="gasoline">น้ำมันเครื่องยนต์ <span class="icon-star">*</span></label>
                        </div>
                        <div class="input-field col s12 m12 l6">
                            <input name="lubricator" id="lubricator" type="text" class="validate" required>
                            <label for="lubricator">น้ำมันห้องเครื่อง <span class="icon-star">*</span></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 m12 l6">
                            <input name="gearBoxOil" id="gearBoxOil" type="text" class="validate" required>
                            <label for="gearBoxOil">น้ำมันห้องเกียร์ <span class="icon-star">*</span></label>
                        </div>
                        <div class="input-field col s12 m12 l6">
                            <input name="finalGearOil" id="finalGearOil" type="text" class="validate" required>
                            <label for="finalGearOil">น้ำมันเฟืองท้าย <span class="icon-star">*</span></label>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h5>ข้อมูลล้อรถ</h5>                                  
                    <div class="row">
                        <div class="input-field col s6 m6 l6">
                            <input name="rowOfWheel" id="rowOfWheel" type="number" min="2" max="30" class="validate" v-model="rowsOfWheel" required>
                            <label for="rowOfWheel">จำนวนแถวของล้อ <span class="icon-star">*</span></label>
                        </div>
                        <div class="col s6 m6 l6">

                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6 m6 l4" v-for="row in parseInt(rowsOfWheel)">
                            <input name="numberWheelPerRow[]" type="number" class="validate" value="2" required>
                            <label for="numberWheelPerRow">จำนวนล้อ/แถว {{ row }} <span class="icon-star">*</span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="modal-action waves-effect waves-green btn-flat">เพิ่ม</button>
                <button type="button" class="modal-close modal-action waves-effect waves-green btn-flat">ปิด</button>
            </div>
        </form>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        data() {
            return {
                csrfToken: window.Laravel.csrfToken,
                photoPreview: '/images/truck_icon.png',
				rowsOfWheel: 2,
            }
        },
		updated: function() {
            Materialize.updateTextFields()
        },
        methods: {
            onImageChange(e) {
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                var image = new Image();
                var reader = new FileReader();

                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            },
        }
    }
</script>