export interface file {
  name: string;
}

export interface flash {
  message: string;
  type: string;
}

export interface user {
  id: number;
  name: string;
  email: string;
  avatar: string;
  email_verified_at: string;
  created_at: string;
  updated_at: string;
  phone: string;
}

export interface tag {
  slug: string;
  color: string;
}

export interface task {
  id: number;
  title: string;
  description: string;
  due_date: string;
  priority: number;
  status: string;
  assignee: user;
  assigner: user;
  created_by: user;
  created_at: string;
  updated_at: string;
  sub_tasks: subTask[];
  tags: tag[];
}

export interface subTask {
  id: number;
  title: string;
  description: string;
  due_date: string;
  priority: number;
  status: string;
  assignee: user;
  assigner: user;
  created_by: user;
  created_at: string;
  updated_at: string;
  tags: tag[];
}

export interface task_list {
  id: number;
  name: string;
  tasks: task[];
}

export interface project {
  id: number;
  title: string;
  description: string;
  created_at: string;
  updated_at: string;
  created_by: user;
  type: string;
  task_lists: task_list[];
}

export interface task_message {
  id?: number;
  content: string;
  sent_by: user;
}
