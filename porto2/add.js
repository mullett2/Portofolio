function addAnak() {
    let div = document.createElement("div");
    div.classList.add("anak");
    div.innerHTML = `<div class="anak">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Nama Anak</label>
                        <input type="text" name="anak_nama[]" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="anak_tanggal[]" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <button type="button" onclick="removeRow(this)" class="text-gray-700 bg-gray-300 hover:bg-gray-400 focus:ring-4 focus:outline-none 
                        focus:ring-gray-200 font-medium rounded-sm text-sm px-5 py-2.5 text-center">Hapus</button>   `;
    document.getElementById("anakForm").appendChild(div);
}

function addPasangan() {
    let div = document.createElement("div");
    div.classList.add("pasangan");
    div.innerHTML = `Nama: <input type="text" name="pasangan_nama[]">
    Tanggal Lahir: <input type="date" name="pasangan_tanggal[]">
    Pendidikan: <input type="text" name="pasangan_pendidikan[]">
    Tanggal Menikah: <input type="date" name="pasangan_menikah[]">
    <button type="button" onclick="removeRow(this)">Hapus</button><br>`;
    document.getElementById("pasanganForm").appendChild(div);
}

function removeRow(btn) {
    btn.parentElement.remove();
}
