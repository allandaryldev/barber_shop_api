<template>
  <div class="pt-4 shadow-2xl">
    <Card class="rounded-lg">
      <template #header>
        <div class="px-2 py-4">
          <PageTitle page_title="Users">
            <div class="flex justify-end">
              <b-tooltip
                class="d-flex"
                type="is-dark"
                label="Create User"
                position="is-left"
              >
                <v-btn color="primary" outlined rounded @click="handleAddModal"
                  >Add User</v-btn
                >
              </b-tooltip>
            </div>
          </PageTitle>
        </div>
      </template>
      <template #content>
        <DataTable
          ref="dt"
          :loading="loading"
          :value="users.data"
          dataKey="id"
          class="p-datatable-customers p-datatable-striped p-datatable-sm"
          :rows="10"
          :filters="filters"
          :expandedRows.sync="expandedRows"
          :rowsPerPageOptions="[10, 20, 50]"
          rowHover
          paginator
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          lazy
          :totalRecords="users_meta.total"
          @page="onPage($event)"
          @sort="onSort($event)"
          :alwaysShow="false"
          :selection.sync="selectedUser"
          @row-select="handleShowModal(selectedUser)"
          @row-unselect="handleShowModal({})"
          selectionMode="single"
        >
          <template #loading> Loading Users data. Please wait. </template>
          <template #empty> No Users found. </template>
          <!-- <Column field="initials" header="initials">
            <template #body="slotProps">
              <div class="">
                <Avatar
                  class="capitalze"
                  :label="slotProps.data.initials"
                  shape="circle"
                />
              </div>
            </template>
          </Column> -->
          <Column field="id" header="id" sortable>
            <template #filter>
              <InputText
                type="text"
                v-model="filters['id']"
                class="p-column-filter p-inputtext-sm"
                placeholder=" Id"
                @keydown.enter="onFilter($event)"
              />
            </template>
          </Column>
          <Column field="name" header="Name">
            <template #body="slotProps">
              <p class="truncate capitalize">
                {{ slotProps.data.name }}
              </p>
            </template>
            <template #filter>
              <InputText
                type="text"
                v-model="filters['name']"
                class="p-column-filter p-inputtext-sm"
                placeholder=" Name"
                @keydown.enter="onFilter($event)"
              />
            </template>
          </Column>
          <Column field="username" header="Username">
          <template #filter>
              <InputText
                type="text"
                v-model="filters['username']"
                class="p-column-filter p-inputtext-sm"
                placeholder=" USername"
                @keydown.enter="onFilter($event)"
              />
            </template>
            <template #body="slotProps">
              <p class="truncate">
                {{ slotProps.data.username }}
              </p>
            </template>
          </Column>
          <Column field="type" header="User Type">
            <template #body="slotProps">
              <p class="truncate uppercase">{{ slotProps.data.type }}</p>
            </template>
          </Column>
        </DataTable>
      </template>
    </Card>

    <UsersAdd :modal="addUserModal" :close="handleAddModal" :get="getUsers" />
    <UsersShow :modal="showUserModal" :close="handleShowModal" :get="getUsers"  :user_id="selectedUser.id" />
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  data() {
    return {
      loading: true,
      addUserModal: false,
      deleteUserModal: false,
      showUserModal: false,
      selected: {},
      expandedRows: [],
      lazyParams: {},
      filters: {},
      selectedUser: {},
    }
  },
  async created() {
    await this.getUsers()
  },
  mounted() {
    this.lazyParams = {
      first: 1,
      rows: this.$refs.dt.rows,
      sortField: null,
      sortOrder: null,
      filters: this.filters,
    }
  },
  computed: {
    ...mapGetters({
      users: 'users/users',
    }),
    users_meta() {
      return { ...this.users.meta }
    },
  },
  methods: {
    ...mapActions({
      get: 'users/get',
      delete: 'users/delete',
    }),

    onPage(event) {
      this.lazyParams = event
      this.getUsers()
    },
    onSort(event) {
      this.lazyParams = event
      this.getUsers()
    },
    onFilter() {
      this.lazyParams.first = 1
      this.getUsers()
    },
    async getUsers() {
      try {
        this.loading = true
        const params = {
          params: {
            rows: this.lazyParams.rows,
            order_by: this.lazyParams.sortField,
            direction: this.lazyParams.sortOrder === 1 ? 'asc' : 'desc',
            // page: this.lazyParams.page + 1,
            page: this.lazyParams.page ? this.lazyParams.page + 1 : 1,
            ...this.filters,
          },
        }
        await this.get(params)
        this.loading = false
      } catch (error) {}
    },
    handleAddModal() {
      this.addUserModal = !this.addUserModal
    },
    handleShowModal() {
      },
    handleShowModal(query) {
      this.showUserModal = !this.showUserModal
    },
  },
}
</script>

<style></style>
