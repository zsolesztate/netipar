<template>
  
    <div class="fixed-top">
      <div class="main-block">

        <form enctype="multipart/form-data" @submit.prevent="saveForm">
          <h2>Kontakt módosítása</h2>
        <div>
          <label for="name">Név:</label>
          <input type="text" id="name" v-model="formData.contact_name" required /> 
        </div>
        <button class="bn632-hover bn23" @click="toggleEditingProfileImage">Profilkép szerkesztése</button>
        

    <div v-if="isEditingProfileImage">
      <label for="image">Válassz új profilképet:</label>
      <input type="file" name="image" @change="handleImageChange" />
      <button class="bn632-hover bn23" @click="cancelEditing">Mégse</button>
      <button class="bn632-hover bn23" @click="deleteProfileImage">Profilkép törlése</button>
    </div>

   

   
        <div v-for="(email, index) in formData.contacts_emails" :key="index">
          <div v-if="!email.deleted">
        <label :for="'email' + index">Email {{ index + 1 }}:</label>
        <input type="text" :id="'email' + index" v-model="email.contact_email_address" />
        <button type="button" class="bn632-hover bn23" @click="removeEmail(index)">Törlés</button>
        <button type="button" class="bn632-hover bn23" @click="addEmailField">Plusz Email</button>
      </div>
    
    </div>

   
       <div v-for="(phone, index) in formData.contacts_phonenumbers" :key="index">
  <div v-if="!phone.deleted">
    <!-- Csak azok az elemek jelennek meg, amelyek nincsenek törölve -->
    <label :for="'phone' + index">Telefon {{ index + 1 }}:</label>
    <input type="text" :id="'phone' + index" v-model="phone.contact_phone_number" />
    <button type="button" class="bn632-hover bn23" @click="removePhoneNumber(index)">Törlés</button>
    <button type="button" class="bn632-hover bn23" @click="addPhoneNumberField">Plusz Telefon</button>
  </div>
</div>
      <div>
        <input type="checkbox" id="sameAddress" v-model="sameAddress" />
        <label for="sameAddress">Levelező cím megegyezik az állandó címmel</label>
      </div>
      <div>
        <label for="permanentAddress">Állandó cím:</label>
        <input type="text" id="permanentAddress" v-model="formData.contacts_addresses.residential_address" />
      </div>
      <div v-if="!sameAddress">
        <label for="mailingAddress">Levelező cím:</label>
        <input type="text" id="mailingAddress" v-model="formData.contacts_addresses.mailing_address" />
      </div>
        <button class="bn632-hover bn25" type="submit">Mentés</button>
        <button class="bn632-hover bn24" type="button" @click="closeModal">Mégsem</button>
      </form>
    </div>
  </div>
  </template>
  
  <script>

import axios from 'axios';
import { useRouter } from 'vue-router'; 

export default {
    props: {
      contact: {
        type: Object,
        required: true,
      },
    },
    data() {
      return {
        formData: {
        contact_name: this.contact.contact_name,
        contact_id: '',
        contacts_emails: [...this.contact.contacts_emails],
        contacts_phonenumbers: [...this.contact.contacts_phonenumbers],
        contacts_addresses: { ...this.contact.contacts_addresses },
        contact_profileimage : '',
        isProfileImageDeleted: null,
        },
        isEditingProfileImage: false,
        sameAddress: true
      }
    },
    watch: {
      sameAddress(newVal) {
        if (newVal) {
          this.formData.contacts_addresses.mailing_address = this.formData.contacts_addresses.residential_address;
        }
      },
    contact: {
      handler(newVal) {
        this.formData = {
          contact_name: newVal.contact_name,
          contacts_emails: [...newVal.contacts_emails],
          contacts_phonenumbers: [...newVal.contacts_phonenumbers],
          contacts_addresses: { ...newVal.contacts_addresses },
        };
      },
          immediate: true,
    },
  },
    methods: {
      async saveForm() {
          console.log('Beküldött adatok123:', this.formData);
          const config = {
              headers: { 'content-type': 'multipart/form-data'}
            }
          try {
          const response = await axios.post(('/api/editcontact'), this.formData, config);
    
          if ((response && response.status === 200) || ((response && response.status === 201))) {
            console.log('Response data:', response.data);
           // this.$router.push('/');
           window.location.href = '/';
          } else {
            console.error('Invalid response:', response);
          }

          } catch (error) {

            if (error.response && error.response.status === 422) {
              
              this.handleValidationErrors(error.response.data.errors)
            } else {

              console.error(error);
            }
          }
      },
      toggleEditingProfileImage() {

       this.isEditingProfileImage = !this.isEditingProfileImage
      },
      removePhoneNumber(index) {

        this.formData.contacts_phonenumbers[index].deleted = true;
      },
      removeEmail(index) {

        if(index > 0){
          this.formData.contacts_emails[index].deleted = true;
        }
      },
      addEmailField() {

         this.formData.contacts_emails.push({ contact_email_address: '' });
      },
      addPhoneNumberField() {

        this.formData.contacts_phonenumbers.push({ contact_phone_number: '' });
      },
      handleImageChange(event) {

        const newImage = event.target.files[0];
        this.formData.contact_profileimage = newImage; 
      },
      cancelEditing() {
        this.isEditingProfileImage = false;
        this.isProfileImageDeleted = null; 
      },
      deleteProfileImage() {
        this.formData.isProfileImageDeleted = 'true'; 
      },
      closeModal() {
         this.$emit('close')
         window.location.href = '/'; 
        }
    },
    mounted() {
        console.log('ContactModal létrejött, contact prop:', this.contact);
        this.formData.contact_id = this.contact.id;
    }
  };
  </script>
  <style scoped>

.main-block {
  position: relative;
}

.fixed-top {
  position: absolute;

}
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
/*
button {
   background: #0b6dff;
   border: 0;
   padding: 10px 20px;
   margin-top: 20px;
   color: white;
   border-radius: 20px;
}*/
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

.bn632-hover.bn23 {
  background-image: linear-gradient(
    to right,
    #94b83e,
    #d3d0aa,
    #9a9c9d,
    #d9e021
  );

}

.bn632-hover.bn24 {
  background-image: linear-gradient(
    to right,
    #ed6969,
    #da421c,
    #9a9c9d,
    #514848
  );

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
  