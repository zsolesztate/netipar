<template>  
   <div>   
        <form enctype="multipart/form-data" @submit.prevent="submitForm">
            <h2>Kontakt hozzáadása</h2>
          <div class="mb-3">
            <label for="name" class="form-label">Név:</label>
            <input class="form-control" type="text" id="name" v-model="formData.name" required />
          </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Telefonszám: //vesszővel elválasztva többet is megadhatsz</label>
          <input type="text" v-model="formData.tempPhone" @keyup="addPhones">
          <div v-for="phone in formData.phoneNumbers" :key="phone" class="pill">
            <span @click="removePhone(phone)">{{ phone }}</span> 
        
        </div>
      </div>
      <div class="mb-3">
            <label for="email" class="form-label">email: //vesszővel elválasztva többet is megadhatsz</label>
          <input type="email" v-model="formData.tempEmail" @keyup="addEmails">
          <div v-for="email in formData.emails" :key="email" class="pill">
            <span @click="removeEmail(email)">{{ email }}</span> 
        </div>
      </div>

      <div>
        <input type="checkbox" id="singleAddress" v-model="singleAddress" />
        <label for="singleAddress">A levelezési cím megegyezik az állandó címmel</label>
      </div>

      <div v-if="!singleAddress">
        <div class="mb-3">
          <label for="residentialAddress" class="form-label">Állandó cím:</label>
          <input class="form-control" type="text" id="residentialAddress" v-model="formData.addresses.residentialAddress" />
        </div>
      </div>

      <div>
        <label for="mailingAddress" class="form-label">Levelezési cím:</label>
        <input class="form-control" type="text" id="mailingAddress" v-model="formData.addresses.mailingAddress" :required="!singleAddress"/>
      </div>

      <div class="mb-3">
   <label for="image" class="form-label">Kép kiválasztása:</label>
   <input type="file" name="image" @change="handleImageChange" />
</div>
          <button type="submit" class="bn632-hover bn25">Kontakt hozzáadása</button>
        </form>
    </div>
    
  </template>
  
<script>

import axios from 'axios';
  
export default {
    data() {
      return {
        formData: {
            name: '',
            emails: [],
            tempPhone: '',
            tempEmail: '',
            phoneNumbers: [],
            addresses: {
            residentialAddress: '',
            mailingAddress: ''
            },
            selectedImage: null            
        },
            singleAddress: true
      }
    },
    methods: {
        addPhones(e){
            if (e.key === ',' && this.formData.tempPhone) {
                if (!this.formData.phoneNumbers.includes(this.formData.tempPhone.slice(0, -1))) {
                    this.formData.phoneNumbers.push(this.formData.tempPhone.slice(0, -1))
                }         
                this.formData.tempPhone = ''
            }
        },
        addEmails(e){
            if (e.key === ',' && this.formData.tempEmail) {
                if (!this.formData.emails.includes(this.formData.tempEmail.slice(0, -1))) {
                    this.formData.emails.push(this.formData.tempEmail.slice(0, -1))
                }         
                this.formData.tempEmail = ''
            }
        },
        saveAddresses() {
            if (!this.singleAddress) {
                this.formData.addresses.residentialAddress = this.formData.tempResidentialAddress;
                this.formData.addresses.mailingAddress = this.formData.tempMailingAddress;
            }
        },
        handleImageChange(event) {
            const fileInput = event.target;
            if (fileInput.files.length > 0) {            
                this.formData.selectedImage = fileInput.files[0];
                console.log(this.formData.selectedImage)
            }
        },
        handleValidationErrors(errors) {
            console.error('Validációs hibák:', errors);
        },
        async submitForm() {
            console.log('Beküldött adatok:', this.formData);
            const config = {
                headers: {'content-type': 'multipart/form-data'}
            }
            try {
                const response = await axios.post('/api/contacts', this.formData, config);
                console.log('Response data:', response.data); 
               // this.$router.push('/');
            } catch (error) {
                if (error.response.status === 422) {
                    this.handleValidationErrors(error.response.data.errors);
                } else {
                    console.error(error);
                }
            }
        },
        removePhone(tempPhone) {
            this.formData.phoneNumbers = this.formData.phoneNumbers.filter(item => {
            return tempPhone !== item
            })
        },
        removeEmail(tempEmail) {
            this.formData.emails = this.formData.emails.filter(item => {
            return tempEmail !== item
            })
        },
    }
  }
  </script>
  <style scoped>
  
  form {
      overflow: auto;
    max-width: 720px;
    max-height: 100%;
    margin: 30px auto;
    background: white;
    text-align: left;
    padding: 40px;
    border-radius: 10px;
     font-size: 0.8em;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: bold;
 }
 label {
    color: #aaa;
    display: inline-block;
    margin: 25px 0 15px;
    font-size: 0.8em;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: bold;
 }
 input {
    display: block;
    padding: 10px 6px;
    width: 100%;
    box-sizing: border-box;
    border: none;
    border-bottom: 1px solid #ddd;
    color: #555;
 }
 input, select {
   display: block;
   padding: 10px 6px;
   width: 100%;
   box-sizing: border-box;
   border: none;
   border-bottom: 1px solid #ddd;
   color: #555;
}
input[type="checkbox"] {
   display: inline-block;
   width: 16px;
   margin: 0 10px 0 0;
   position: relative;
   top: 2px;
}
.pill {
   display: inline-block;
   margin: 20px 10px 0 0;
   padding: 6px 12px;
   background: #eee;
   border-radius: 20px;
   font-size: 12px;
   letter-spacing: 1px;
   font-weight: bold;
   color: #777;
   cursor: pointer;
}

.submit {
   text-align: center;
}
.error {
   color: #ff0062;
   margin-top: 10px;
   font-size: 0.8em;
   font-weight: bold;
}
.fixed-top-container {
  position: fixed;
  top: 0;
}

.bn632-hover {
  width: 160px;
  font-size: 16px;
  font-weight: 600;
  color: #fff;
  cursor: pointer;
  margin: 10px;;
  height: 55px;
  text-align:center;
  border: none;
  background-size: 300% 100%;
  moz-transition: all .4s ease-in-out;
  -o-transition: all .4s ease-in-out;
  -webkit-transition: all .4s ease-in-out;
  transition: all .4s ease-in-out;
}

.bn632-hover:hover {
  background-position: 100% 0;
  moz-transition: all .4s ease-in-out;
  -o-transition: all .4s ease-in-out;
  -webkit-transition: all .4s ease-in-out;
  transition: all .4s ease-in-out;
}

.bn632-hover:focus {
  outline: none;
}

.bn632-hover.bn25 {
  background-image: linear-gradient(
    to right,
    #69abed,
    #1c55da,
    #9a9c9d,
    #514848
  );

}
  </style>