import React, { useState } from "react";

export default function CadastroPrato({ onVoltar, onSalvar }) {
  const [form, setForm] = useState({
    nome: "",
    ingredientes: "",
    valor: "",
  });

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    onSalvar(form);
    alert("Prato cadastrado com sucesso!");
    setForm({
      nome: "",
      ingredientes: "",
      valor: "",
    });
  };

  return (
    <div className="flex justify-center items-center min-h-screen bg-gradient-to-br from-green-100 to-green-300 p-6">
      <div className="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div className="flex items-center mb-6">
          <button
            onClick={onVoltar}
            className="text-green-700 hover:text-green-900 mr-4"
            type="button"
          >
            ← Voltar
          </button>
          <h1 className="text-2xl font-bold text-green-700 flex-1 text-center">
            Cadastro de Prato
          </h1>
        </div>

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-600">
              Nome do Prato
            </label>
            <input
              type="text"
              name="nome"
              value={form.nome}
              onChange={handleChange}
              className="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
              required
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-600">
              Ingredientes
            </label>
            <textarea
              name="ingredientes"
              value={form.ingredientes}
              onChange={handleChange}
              placeholder="Separe os ingredientes por vírgula"
              className="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
              rows="3"
              required
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-600">
              Valor (R$)
            </label>
            <input
              type="number"
              step="0.01"
              min="0"
              name="valor"
              value={form.valor}
              onChange={handleChange}
              className="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
              required
            />
          </div>

          <button
            type="submit"
            className="w-full bg-green-600 text-white p-2 rounded-lg font-semibold hover:bg-green-700 transition"
          >
            Cadastrar Prato
          </button>
        </form>
      </div>
    </div>
  );
}
