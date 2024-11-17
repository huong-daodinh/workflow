const axios = window.axios;

export async function getTaskDetail($taskId: number) {
  const response = await axios.get(`/task/${$taskId}`);
  return response;
}

export async function getTaskMessages(taskId: number) {
  const response = await axios.get(`/task/messages/${taskId}`);
  return response;
}

export async function sendTaskMessage(taskId: number, message: string) {
  const response = await axios.post(`/task/send-message/${taskId}`, { message });
  return response;
}

export async function markAsDone(taskId: number) {
  const response = await axios.post(`/task/mark-as-done/${taskId}`);
  return response;
}

export async function createTaskResult(taskId: number, result: string) {
  const response = await axios.post(`/task/update-result/${taskId}`, {
    result: result
  });
  return response;
}
