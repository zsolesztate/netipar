<template>
    <div>
      <div class="modal-overlay" v-if="isEditModalOpen">
          <ContactModal :contact="editContact" @close="toggleModal"></ContactModal>  
      </div>
      <div v-if="contacts.length">
        <div v-for="(contact, index) in contacts" :key="index">
              <ol style="--length: 5" role="list">
              <li style="--i: 3">
                <h3>{{ contact.contact_name }}</h3>
               
                <div class="d-flex">
                  <div style="padding-right: 15px;">
                    <img :src="imageSource(contact)" alt="Profile Image" style="max-height: 200px;"/>
                  </div>
                  <div style="padding-right: 15px;">
                    <h2>Telefonszámok:</h2>
                    <div v-if="contact.contacts_phonenumbers.length">
                      <div v-for="(phoneNumber, phoneIndex) in contact.contacts_phonenumbers" :key="phoneIndex">
                        <p>{{ phoneNumber.contact_phone_number }}</p>
                      </div>
                    </div>
                      <div v-else>
                        Nincsenek elérhetőségek
                      </div>
                      <h2>Emailcímek:</h2>
                    <div v-if="contact.contacts_emails.length">
                      <div v-for="(emailAddress, emailIndex) in contact.contacts_emails" :key="emailIndex">
                        <p>{{ emailAddress.contact_email_address }} </p>
                      </div>
                    </div>
                      <div v-else>
                        Nincsenek Email címek
                      </div>
                    <div v-if="contact.contacts_addresses.residential_address  && contact.contacts_addresses.residential_address !== null">
                        <h2>Állandó cím:</h2>
                        <p> {{contact.contacts_addresses.residential_address}} </p>
                    </div>
                  <div v-else>
                     Nincs állandó cím
                  </div>
                    <div v-if="contact.contacts_addresses.mailing_address && contact.contacts_addresses.mailing_address != null">
                      Levelezési cím
                      <p> {{contact.contacts_addresses.mailing_address}} </p>
                          </div>
                        <div v-else>
                      Nincsenek levelezési címe
                    </div>                  
                  </div> 
                </div>   
                <div class="d-flex justify-content-end">            
                <button  class="bn632-hover bn23"  @click="deleteContact(contact.id)">Törlés</button>
                <button class="bn632-hover bn23" @click="openEditModal(contact)">Szerkesztés</button>
                 </div>         
              </li>         
            </ol>
        </div>
      </div>
    </div>
  
  
  
    
  
  
  </template>
  
  <script>
  
  import axios from 'axios';
  import ContactModal from '../components/ContactModal.vue';
  import NewContact from '../components/NewContact.vue';
  
  export default {
    data() {
      return {
        contacts: [],
        editContact: null,
        isEditModalOpen: false,
      }
    },
        components: { ContactModal, NewContact },
        methods: {
          async fetchContacts() {
            try {
              const response = await axios.get('/api/contacts');
              this.contacts = response.data;
              console.log(response.data)
            } catch (error) {            
              console.log(error);
            }
          },
          imageSource(contact) {
            if (contact.contact_image) {
              return '/storage/' + contact.contact_image;
            } else {
              return '/images/default.jpg';
            }
          },
          async deleteContact(contactId) {
          try {
            const response = await axios.delete(`/api/contacts/${contactId}`);
            console.log(response.data);
            this.fetchContacts();
          } catch (error) {
            console.log(error);
          }
        },
         openEditModal(contact) {
            this.editContact = contact;
            this.isEditModalOpen = true;
        },
        toggleModal() {
        this.isEditModalOpen = !this.isEditModalOpen
      }, 
      },
        mounted() {
           this.fetchContacts();
      }
  };
  </script>
  <style scoped>
  @import url("https://fonts.googleapis.com/css?family=Montserrat:400,700");
  
  * {
      box-sizing: border-box;
  }
  
  body {
      --h: 212deg;
      --l: 43%;
      --brandColor: hsl(var(--h), 71%, var(--l));
      font-family: Montserrat, sans-serif;
      margin: 0;
      background-color: whitesmoke;
  }
  
  p {
      margin: 0;
      line-height: 1.6;
  }
  
  ol {
      list-style: none;
      counter-reset: list;
      padding: 0 1rem;
  }
  
  li {
      --stop: calc(100% / var(--length) * var(--i));
      --l: 62%;
      --l2: 88%;
      --h: calc((var(--i) - 1) * (180 / var(--length)));
      --c1: hsl(var(--h), 71%, var(--l));
      --c2: hsl(var(--h), 71%, var(--l2));
      
      position: relative;
      counter-increment: list;
      max-width: 45rem;
      margin: 2rem auto;
      padding: 2rem 1rem 1rem;
      box-shadow: 0.1rem 0.1rem 1.5rem rgba(0, 0, 0, 0.3);
      border-radius: 0.25rem;
      overflow: hidden;
      background-color: white;
  }
  
  li::before {
      content: '';
      display: block;
      width: 100%;
      height: 1rem;
      position: absolute;
      top: 0;
      left: 0;
      background: linear-gradient(to right, var(--c1) var(--stop), var(--c2) var(--stop));
  }
  
  h3 {
      display: flex;
      align-items: baseline;
      margin: 0 0 1rem;
      color: rgb(70 70 70);
  }
  
  
  
  @media (min-width: 40em) {
      li {
          margin: 3rem auto;
          padding: 3rem 2rem 2rem;
      }
      
      h3 {
          font-size: 2.25rem;
          margin: 0 0 2rem;
      }
      
      h3::before {
          margin-right: 1.5rem;
      }
  }
  
  .bn632-hover {
    width: 160px;
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    cursor: pointer;
    margin: 20px;
    height: 55px;
    text-align:center;
    border: none;
    background-size: 300% 100%;
    border-radius: 5px;
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
    box-shadow: 0 4px 15px 0 rgba(83, 176, 57, 0.75);
  }
  
  
  .modal-overlay {
    overflow: auto;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6); /* Átlátszó fekete háttér */
    z-index: 999; /* Biztosítsd, hogy az overlay a legfelső rétegben legyen */
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  </style>